<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\Customer;

class CustomerCollection extends Collection
{
    public function getModelClass(): string
    {
        return Customer::class;
    }
}
