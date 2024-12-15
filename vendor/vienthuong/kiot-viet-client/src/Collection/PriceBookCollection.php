<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\PriceBook;

class PriceBookCollection extends Collection
{
    public function getModelClass(): string
    {
        return PriceBook::class;
    }
}
