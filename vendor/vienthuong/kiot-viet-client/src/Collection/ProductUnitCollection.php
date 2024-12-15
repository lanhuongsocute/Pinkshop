<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\ProductUnit;

class ProductUnitCollection extends Collection
{
    public function getModelClass(): string
    {
        return ProductUnit::class;
    }
}
