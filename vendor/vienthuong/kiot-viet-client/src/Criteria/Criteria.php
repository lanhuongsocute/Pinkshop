<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Criteria;

use VienThuong\KiotVietClient\Model\BaseModel;

class Criteria
{
    const DEFAULT_PAGE_SIZE = 20;
    const DEFAULT_ORDER_DIRECTION = 'ASC';
    const DEFAULT_INCLUDE_REMOVE_IDS = false;

    public function __construct(?BaseModel $model = null)
    {
        $this->queries = [
            'pageSize' => self::DEFAULT_PAGE_SIZE,
            'orderDirection' => self::DEFAULT_ORDER_DIRECTION,
            'includeRemoveIds' => self::DEFAULT_INCLUDE_REMOVE_IDS
        ];

        if ($model) {
            $this->setModel($model);
        }
    }

    /**
     * @var array
     */
    protected $queries;

    /**
     */
    public function setModel(BaseModel $model): void
    {
        $modelData = array_filter($model->getModelData());
        foreach ($modelData as $attribute => $value) {
            if (isset($value) && (is_numeric($value) || is_string($value))) {
                $this->addCriteria($attribute, $value);
            }
        }
    }

    /**
     */
    public function getQueries(): array
    {
        return $this->queries;
    }

    /**
     */
    public function setQueries(array $queries): void
    {
        $this->queries = $queries;
    }

    public function addCriteria($key, $value): self
    {
        $this->queries[$key] = $value;

        return $this;
    }

    public function removeCriteria($key): self
    {
        unset($this->queries[$key]);

        return $this;
    }

    public function setPageSize(int $pageSize): self
    {
        return $this->addCriteria('pageSize', $pageSize);
    }

    /**
     * @param  $currentItem
     */
    public function setCurrentItem($currentItem): self
    {
        if ($currentItem instanceof BaseModel) {
            $currentItem = $currentItem->getId();
        }

        if (is_int($currentItem)) {
            return $this->addCriteria('currentItem', $currentItem);
        }
    }

    /**
     */
    public function setOrderDirection(string $orderDirection): self
    {
        return $this->addCriteria('orderDirection', $orderDirection);
    }

    /**
     */
    public function setIncludeRemoveIds(bool $includeRemoveIds): self
    {
        return $this->addCriteria('includeRemoveIds', $includeRemoveIds);
    }

    /**
     */
    public function setLastModifiedFrom(string $lastModifiedFrom): self
    {
        return $this->addCriteria('lastModifiedFrom', $lastModifiedFrom);
    }

    /**
     */
    public function setOrderBy(string $orderBy): self
    {
        return $this->addCriteria('orderBy', $orderBy);
    }
}
