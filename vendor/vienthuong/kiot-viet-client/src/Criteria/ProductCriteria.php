<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Criteria;

use VienThuong\KiotVietClient\Model\BaseModel;
use VienThuong\KiotVietClient\Model\Product;

class ProductCriteria extends Criteria
{
    public function setIncludeInventory(bool $includeInventory): self
    {
        return $this->addCriteria('includeInventory', $includeInventory);
    }

    public function setIncludePriceBook(bool $includePriceBook): self
    {
        return $this->addCriteria('includePriceBook', $includePriceBook);
    }

    public function setIncludeSerials(bool $IncludeSerials): self
    {
        return $this->addCriteria('includeSerials', $IncludeSerials);
    }

    public function setIncludeBatchExpires(bool $includeBatchExpires): self
    {
        return $this->addCriteria('includeBatchExpires', $includeBatchExpires);
    }

    public function setIncludeMaterial(bool $includeMaterial): self
    {
        return $this->addCriteria('includeMaterial', $includeMaterial);
    }

    public function setProductType(int $productType): self
    {
        return $this->addCriteria('productType', $productType);
    }

    public function setCategoryId(int $categoryId): self
    {
        return $this->addCriteria('categoryId', $categoryId);
    }

    public function setMasterUnitId(int $masterUnitId): self
    {
        return $this->addCriteria('masterUnitId', $masterUnitId);
    }
}
