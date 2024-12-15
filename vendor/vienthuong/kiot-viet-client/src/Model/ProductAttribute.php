<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

/**
 * Class ProductAttribute
 * @package VienThuong\KiotVietClient\Model
 *
 * Thuộc tính hàng hoá
 */
class ProductAttribute extends BaseModel
{
    /**
     * @var int
     */
    private $productId;

    /**
     * @var int
     */
    private $attributeId;

    /**
     * @var string;
     */
    private $attributeName;

    /**
     * @var string
     */
    private $attributeValue;

    /**
     */
    public function getAttributeName(): string
    {
        return $this->attributeName;
    }

    /**
     */
    public function setAttributeName(string $attributeName): void
    {
        $this->attributeName = $attributeName;
    }

    /**
     */
    public function getAttributeValue(): string
    {
        return $this->attributeValue;
    }

    /**
     */
    public function setAttributeValue(string $attributeValue): void
    {
        $this->attributeValue = $attributeValue;
    }

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
     * @return int
     */
    public function getAttributeId(): ?int
    {
        return $this->attributeId;
    }

    /**
     * @param  int  $attributeId
     */
    public function setAttributeId(?int $attributeId): void
    {
        $this->attributeId = $attributeId;
    }

    /**
     * Handle special case
     */
    protected function mapTypes(array $properties = []): void
    {
        if (isset($properties['name'])) {
            $this->setAttributeName($properties['name']);
            unset($properties['name']);
        }

        parent::mapTypes($properties);
    }
}
