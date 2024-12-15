<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\Surcharge;

class SurchargeCollection extends Collection
{
    public function getModelClass(): string
    {
        return Surcharge::class;
    }
}
