<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\OrderDetail;

class OrderDetailCollection extends Collection
{
    public function getModelClass(): string
    {
        return OrderDetail::class;
    }
}
