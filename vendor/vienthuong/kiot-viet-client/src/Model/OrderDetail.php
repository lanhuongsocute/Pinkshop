<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

class OrderDetail extends BaseModel
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
}
