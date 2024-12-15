<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\Product;

class ProductCollection extends Collection
{
    public function getModelClass(): string
    {
        return Product::class;
    }
}
