<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use VienThuong\KiotVietClient\Collection\Collection;
use VienThuong\KiotVietClient\Model\UnknownModel;

class CustomResource extends BaseResource
{
    /**
     * @var string
     */
    private $endPoint;

    /**
     * @var string
     */
    private $expectedModel;

    /**
     * @var string
     */
    private $collectionClass;

    /**
     */
    public function getExpectedModel(): string
    {
        return $this->expectedModel ?? UnknownModel::class;
    }

    /**
     * @throws \Exception
     */
    public function setExpectedModel(string $expectedModel): self
    {
        if (!class_exists($expectedModel)) {
            throw new \Exception("Expected Model $expectedModel is not existed");
        }

        $this->expectedModel = $expectedModel;
        return $this;
    }

    /**
     */
    public function getCollectionClass(): string
    {
        return $this->collectionClass ?? Collection::class;
    }

    /**
     * @throws \Exception
     */
    public function setCollectionClass(string $collectionClass): self
    {
        if (!class_exists($collectionClass)) {
            throw new \Exception("Collection $collectionClass is not existed");
        }

        $this->collectionClass = $collectionClass;
        return $this;
    }

    public function setEndPoint(string $endPoint): self
    {
        $this->endPoint = $endPoint;
        return $this;
    }

    public function getEndPoint() : string
    {
        return $this->endPoint;
    }
}
