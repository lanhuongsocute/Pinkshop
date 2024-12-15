<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

class InvoiceDetail extends BaseModel
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
    private $productName;

    /**
     * @var int|null
     */
    private $quantity;

    /**
     * @var float|null
     */
    private $price;

    /**
     * @var float|null
     */
    private $discountRatio;

    /**
     * @var float|null
     */
    private $discount;

    /**
     * @var string|null
     */
    private $note;

    /**
     * @var array|null
     */
    private $productBatchExpire;

    /**
     * @var string|null
     */
    private $serialNumbers;

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
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     */
    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
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
    public function getDiscountRatio(): ?float
    {
        return $this->discountRatio;
    }

    /**
     */
    public function setDiscountRatio(?float $discountRatio): void
    {
        $this->discountRatio = $discountRatio;
    }

    /**
     */
    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    /**
     */
    public function setDiscount(?float $discount): void
    {
        $this->discount = $discount;
    }

    /**
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     */
    public function setNote(?string $note): void
    {
        $this->note = $note;
    }

    /**
     */
    public function getSerialNumbers(): ?string
    {
        return $this->serialNumbers;
    }

    /**
     */
    public function setSerialNumbers(?string $serialNumbers): void
    {
        $this->serialNumbers = $serialNumbers;
    }

    /**
     */
    public function getProductBatchExpire(): ?array
    {
        return $this->productBatchExpire;
    }

    /**
     */
    public function setProductBatchExpire(?array $productBatchExpire): void
    {
        $this->productBatchExpire = $productBatchExpire;
    }

    /**
     */
    public function getProductName(): ?string
    {
        return $this->productName;
    }

    /**
     */
    public function setProductName(?string $productName): void
    {
        $this->productName = $productName;
    }
}
