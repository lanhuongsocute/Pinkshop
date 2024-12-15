<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\Inventory;

class InventoryCollection extends Collection
{
    public function getModelClass(): string
    {
        return Inventory::class;
    }
}
