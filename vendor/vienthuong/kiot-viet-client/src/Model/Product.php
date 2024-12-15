<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

use VienThuong\KiotVietClient\Collection\InventoryCollection;
use VienThuong\KiotVietClient\Collection\ProductAttributeCollection;
use VienThuong\KiotVietClient\Collection\ProductUnitCollection;

/**
 * Class Product
 * @package VienThuong\KiotVietClient\Model
 *
 * Hàng hoá
 */
class Product extends BaseModel
{
    /**
     * Hàng combo, sản xuất
     */
    const PRODUCT_TYPE_COMBO_OR_OEM = 1;

    /**
     * Hàng hóa thông thường
     */
    const PRODUCT_TYPE_NORMAL = 2;

    /**
     * Hàng hoá dịch vụ
     */
    const PRODUCT_TYPE_SERVICE = 3;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $fullName;

    /**
     * @var array
     */
    private $images;

    /**
     * @var int
     */
    private $categoryId;

    /**
     * @var string
     */
    private $categoryName;

    /**
     * @var bool
     */
    private $allowsSale;

    /**
     * @var string
     */
    private $description;

    /**
     * @var bool
     */
    private $hasVariants;

    /**
     * @var ProductAttributeCollection
     */
    private $attributes;

    /**
     * @var InventoryCollection
     */
    private $inventories;

    /**
     * @var string
     */
    private $unit;

    /**
     * @var int
     */
    private $masterUnitId;

    /**
     * @var int
     */
    private $conversionValue;

    /**
     * @var int
     */
    private $productType;

    /**
     * @var float
     */
    private $basePrice;

    /**
     * @var float
     */
    private $weight;

    /**
     * @var bool
     */
    private $isActive;

    /**
     * @var bool
     */
    private $isRewardPoint;

    /**
     * @var string
     */
    private $tradeMarkName;

    /**
     * @var int
     */
    private $masterProductId;

    /**
     * @var string
     */
    private $orderTemplate;

    /**
     * @var ProductUnitCollection
     */
    private $units;

    /**
     * @var bool
     */
    private $isLotSerialControl;

    /**
     * @var string
     */
    private $masterCode;

    /**
     * @return string
     */
    public function getTradeMarkName(): ?string
    {
        return $this->tradeMarkName;
    }

    /**
     */
    public function setTradeMarkName(string $tradeMarkName): void
    {
        $this->tradeMarkName = $tradeMarkName;
    }

    /**
     * @return int
     */
    public function getMasterProductId(): ?int
    {
        return $this->masterProductId;
    }

    /**
     */
    public function setMasterProductId(int $masterProductId): void
    {
        $this->masterProductId = $masterProductId;
    }

    /**
     * @return string
     */
    public function getOrderTemplate(): ?string
    {
        return $this->orderTemplate;
    }

    /**
     */
    public function setOrderTemplate(string $orderTemplate): void
    {
        $this->orderTemplate = $orderTemplate;
    }

    /**
     * @return bool
     */
    public function getIsLotSerialControl(): ?bool
    {
        return $this->isLotSerialControl;
    }

    /**
     */
    public function setIsLotSerialControl(bool $isLotSerialControl): void
    {
        $this->isLotSerialControl = $isLotSerialControl;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     */
    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * @return array
     */
    public function getImages(): ?array
    {
        return $this->images;
    }

    /**
     */
    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    /**
     * @return int
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    /**
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return string
     */
    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    /**
     */
    public function setCategoryName(string $categoryName): void
    {
        $this->categoryName = $categoryName;
    }

    /**
     * @return bool
     */
    public function getIsAllowsSale(): ?bool
    {
        return $this->allowsSale;
    }

    /**
     */
    public function setAllowsSale(bool $allowsSale): void
    {
        $this->allowsSale = $allowsSale;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function getIsHasVariants(): ?bool
    {
        return $this->hasVariants;
    }

    /**
     */
    public function setHasVariants(bool $hasVariants): void
    {
        $this->hasVariants = $hasVariants;
    }

    /**
     * @return ProductAttributeCollection
     */
    public function getAttributes(): ?ProductAttributeCollection
    {
        return $this->attributes;
    }

    /**
     * @param  ProductAttributeCollection  $attributes
     */
    public function setAttributes(?ProductAttributeCollection $attributes): void
    {
        $this->attributes = $attributes;
    }

    /**
     * @return InventoryCollection
     */
    public function getInventories(): ?InventoryCollection
    {
        return $this->inventories;
    }

    /**
     * @param  InventoryCollection  $inventories
     */
    public function setInventories(?InventoryCollection $inventories): void
    {
        $this->inventories = $inventories;
    }

    /**
     * @return string
     */
    public function getUnit(): ?string
    {
        return $this->unit;
    }

    /**
     */
    public function setUnit(string $unit): void
    {
        $this->unit = $unit;
    }

    /**
     * @return int
     */
    public function getMasterUnitId(): ?int
    {
        return $this->masterUnitId;
    }

    /**
     */
    public function setMasterUnitId(int $masterUnitId): void
    {
        $this->masterUnitId = $masterUnitId;
    }

    /**
     * @return int
     */
    public function getConversionValue(): ?int
    {
        return $this->conversionValue;
    }

    /**
     */
    public function setConversionValue(int $conversionValue): void
    {
        $this->conversionValue = $conversionValue;
    }

    /**
     * @return float
     */
    public function getBasePrice(): ?float
    {
        return $this->basePrice;
    }

    /**
     */
    public function setBasePrice(float $basePrice): void
    {
        $this->basePrice = $basePrice;
    }

    /**
     * @return float
     */
    public function getWeight(): ?float
    {
        return $this->weight;
    }

    /**
     */
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    /**
     * @return bool
     */
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return bool
     */
    public function getIsRewardPoint(): ?bool
    {
        return $this->isRewardPoint;
    }

    /**
     */
    public function setIsRewardPoint(bool $isRewardPoint): void
    {
        $this->isRewardPoint = $isRewardPoint;
    }

    /**
     * @return int
     */
    public function getProductType(): ?int
    {
        return $this->productType;
    }

    /**
     */
    public function setProductType(int $productType): void
    {
        $this->productType = $productType;
    }

    /**
     * @return ProductUnitCollection
     */
    public function getUnits(): ?ProductUnitCollection
    {
        return $this->units;
    }

    /**
     * @param  ProductUnitCollection  $units
     */
    public function setUnits(?ProductUnitCollection $units): void
    {
        $this->units = $units;
    }

    /**
     * @return string
     */
    public function getMasterCode(): ?string
    {
        return $this->masterCode;
    }

    /**
     * @param  string  $masterCode
     */
    public function setMasterCode(?string $masterCode): void
    {
        $this->masterCode = $masterCode;
    }
}
