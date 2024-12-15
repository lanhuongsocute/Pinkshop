<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

class Inventory extends BaseModel
{
    /**
     * @var int
     */
    private $branchId;

    /**
     * @var string
     */
    private $branchName;

    /**
     * @var float
     */
    private $onHand;

    /**
     * @var float
     */
    private $cost;

    /**
     * @var float
     */
    private $reserved;

    /**
     * @var int
     */
    private $productId;

    /**
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     */
    public function getMaxQuantity(): int
    {
        return $this->maxQuantity;
    }

    /**
     */
    public function setMaxQuantity(int $maxQuantity): void
    {
        $this->maxQuantity = $maxQuantity;
    }

    /**
     * @var int
     */
    private $minQuantity;

    /**
     * @var int
     */
    private $maxQuantity;

    /**
     */
    public function getBranchId(): int
    {
        return $this->branchId;
    }

    /**
     */
    public function setBranchId(int $branchId): void
    {
        $this->branchId = $branchId;
    }

    /**
     */
    public function getBranchName(): string
    {
        return $this->branchName;
    }

    /**
     */
    public function setBranchName(string $branchName): void
    {
        $this->branchName = $branchName;
    }

    /**
     */
    public function getOnHand(): float
    {
        return $this->onHand;
    }

    /**
     */
    public function setOnHand(float $onHand): void
    {
        $this->onHand = $onHand;
    }

    /**
     */
    public function getCost(): float
    {
        return $this->cost;
    }

    /**
     */
    public function setCost(float $cost): void
    {
        $this->cost = $cost;
    }

    /**
     */
    public function getReserved(): float
    {
        return $this->reserved;
    }

    /**
     */
    public function setReserved(float $reserved): void
    {
        $this->reserved = $reserved;
    }

    /**
     */
    public function getMinQuantity(): int
    {
        return $this->minQuantity;
    }

    /**
     */
    public function setMinQuantity(int $minQuantity): void
    {
        $this->minQuantity = $minQuantity;
    }
}
