<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\Branch;

class BranchCollection extends Collection
{
    public function getModelClass(): string
    {
        return Branch::class;
    }
}
