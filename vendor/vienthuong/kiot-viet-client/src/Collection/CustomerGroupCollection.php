<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\CustomerGroup;

class CustomerGroupCollection extends Collection
{
    public function getModelClass(): string
    {
        return CustomerGroup::class;
    }
}
