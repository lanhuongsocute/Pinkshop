<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

class PriceBook extends BaseModel
{
    /**
     * @var int|null
     */
    private $productId;

    /**
     * @var string|null
     */
    private $productCode;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var float|null
     */
    private $price;

    /**
     * @var bool|null
     */
    private $isActive;

    /**
     * @var bool|null
     */
    private $isGlobal;

    /**
     * @var string|null
     */
    private $startDate;

    /**
     * @var string|null
     */
    private $endDate;

    /**
     * @var bool|null
     */
    private $forAllCusGroup;

    /**
     * @var bool|null
     */
    private $forAllUser;

    /**
     * @var array|null
     */
    private $priceBookBranches;

    /**
     * @var array|null
     */
    private $priceBookCustomerGroups;

    /**
     * @var array|null
     */
    private $priceBookUsers;

    /**
     */
    public function getProductId(): ?int
    {
        return $this->productId;
    }

    /**
     */
    public function setProductId(?int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     */
    public function getProductCode(): ?string
    {
        return $this->productCode;
    }

    /**
     */
    public function setProductCode(?string $productCode): void
    {
        $this->productCode = $productCode;
    }

    /**
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     */
    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    /**
     */
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     */
    public function setIsActive(?bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     */
    public function getIsGlobal(): ?bool
    {
        return $this->isGlobal;
    }

    /**
     */
    public function setIsGlobal(?bool $isGlobal): void
    {
        $this->isGlobal = $isGlobal;
    }

    /**
     */
    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    /**
     */
    public function setStartDate(?string $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     */
    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    /**
     */
    public function setEndDate(?string $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     */
    public function getForAllCusGroup(): ?bool
    {
        return $this->forAllCusGroup;
    }

    /**
     */
    public function setForAllCusGroup(?bool $forAllCusGroup): void
    {
        $this->forAllCusGroup = $forAllCusGroup;
    }

    /**
     */
    public function getForAllUser(): ?bool
    {
        return $this->forAllUser;
    }

    /**
     */
    public function setForAllUser(?bool $forAllUser): void
    {
        $this->forAllUser = $forAllUser;
    }

    /**
     */
    public function getPriceBookBranches(): ?array
    {
        return $this->priceBookBranches;
    }

    /**
     */
    public function setPriceBookBranches(?array $priceBookBranches): void
    {
        $this->priceBookBranches = $priceBookBranches;
    }

    /**
     */
    public function getPriceBookCustomerGroups(): ?array
    {
        return $this->priceBookCustomerGroups;
    }

    /**
     */
    public function setPriceBookCustomerGroups(?array $priceBookCustomerGroups): void
    {
        $this->priceBookCustomerGroups = $priceBookCustomerGroups;
    }

    /**
     */
    public function getPriceBookUsers(): ?array
    {
        return $this->priceBookUsers;
    }

    /**
     */
    public function setPriceBookUsers(?array $priceBookUsers): void
    {
        $this->priceBookUsers = $priceBookUsers;
    }
}
