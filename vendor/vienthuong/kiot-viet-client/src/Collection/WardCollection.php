<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\Ward;

class WardCollection extends Collection
{
    public function getModelClass(): string
    {
        return Ward::class;
    }
}
