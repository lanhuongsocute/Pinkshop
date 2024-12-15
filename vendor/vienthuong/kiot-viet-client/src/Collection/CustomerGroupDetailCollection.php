<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\CustomerGroupDetail;

class CustomerGroupDetailCollection extends Collection
{
    public function getModelClass(): string
    {
        return CustomerGroupDetail::class;
    }
}
