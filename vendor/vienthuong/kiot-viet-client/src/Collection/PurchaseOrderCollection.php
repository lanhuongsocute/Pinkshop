<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\PurchaseOrder;

class PurchaseOrderCollection extends Collection
{
    public function getModelClass(): string
    {
        return PurchaseOrder::class;
    }
}
