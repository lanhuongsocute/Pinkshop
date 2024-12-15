<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use ArrayObject;
use VienThuong\KiotVietClient\Model\BaseModel;
use VienThuong\KiotVietClient\Model\UnknownModel;
use VienThuong\KiotVietClient\Utils\ExtendableTrait;

class Collection extends ArrayObject implements CollectionInterface
{
    use ExtendableTrait;

    /**
     * @var string
     */
    protected $modelClass;

    /**
     * @var array
     */
    protected $items;

    /**
     * @var  int
     */
    protected $pageSize;

    /**
     * @var string
     */
    protected $timestamp;

    /**
     * @var array
     */
    protected $removedIds;

    /**
     * @var  int
     */
    protected $total;

    public function __construct(?array $items = [])
    {
        parent::__construct([]);

        if (!class_exists($this->getModelClass())) {
            throw new \InvalidArgumentException($this->getModelClass() . " must exists");
        }

        if (!empty($items)) {
            $this->setItems($items);
        }
    }

    public function add(BaseModel ...$items): void
    {
        foreach ($items as $item) {
            $this->append($item);
        }
    }

    public function append($value): void
    {
        $this->validateType($value);
        parent::append($value);
    }

    /**
     */
    public function toArray(): array
    {
        return array_map(function (BaseModel $baseModel) {
            return $baseModel->getModelData();
        }, $this->items ?? []);
    }

    /**
     */
    public function getModelClass(): string
    {
        return UnknownModel::class;
    }

    /**
     */
    public function getTotal(): int
    {
        return $this->total ?? $this->count();
    }

    /**
     */
    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    /**
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     */
    public function setPageSize(int $pageSize): void
    {
        $this->pageSize = $pageSize;
    }

    public function filter(\Closure $closure): ?CollectionInterface
    {
        $filteredItems = array_filter($this->getItems(), $closure);

        $collection = clone $this;
        $collection->exchangeArray($filteredItems);
        $collection->setTotal(count($filteredItems));

        return $collection;
    }

    public function filterByProperty(string $propertyName, $value): ?CollectionInterface
    {
        $getter = 'get' . ucfirst($propertyName);

        return $this->filter(function (BaseModel $model) use ($getter, $propertyName, $value) {
            if (!method_exists($model, $getter)) {
                throw new \InvalidArgumentException(
                    sprintf('Expected property %s exist in %s', $propertyName, get_class($model))
                );
            }

            return $model->$getter() === $value;
        });
    }

    public function first() : ?BaseModel
    {
        return array_values($this->items)[0] ?? null;
    }

    public function last() : ?BaseModel
    {
        return array_values($this->items)[\count($this->items) - 1] ?? null;
    }

    public function all(): ?array
    {
        return $this->getArrayCopy();
    }

    protected function validateType($element): void
    {
        $expectedClass = $this->getModelClass();
        if ($expectedClass === null) {
            return;
        }

        if (!$element instanceof $expectedClass) {
            $elementClass = \get_class($element);

            throw new \InvalidArgumentException(
                sprintf('Expected collection element of type %s got %s', $expectedClass, $elementClass)
            );
        }
    }

    /**
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * mixed $timestamp
     */
    public function setTimestamp($timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     */
    public function getRemovedIds(): array
    {
        return $this->removedIds;
    }

    /**
     */
    public function setRemovedIds(array $removedIds): void
    {
        $this->removedIds = $removedIds;
    }

    /**
     */
    public function setItems(array $items): void
    {
        if (method_exists($this, 'transformItems')) {
            $transformItems = $this->transformItems($items);
            $items = count($transformItems)  > 0 ? $transformItems : $items;
        }

        $items = array_map(function ($item) {
            $expectedModel = $this->getModelClass();
            return $item instanceof $expectedModel ? $item : new $expectedModel($item);
        }, $items);

        $this->items = $this->items ? array_merge($this->items, $items) : $items;
        $this->add(...$items);
    }
}
