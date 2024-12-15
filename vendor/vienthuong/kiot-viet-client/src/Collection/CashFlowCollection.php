<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\CashFlow;

class CashFlowCollection extends Collection
{
    public function getModelClass(): string
    {
        return CashFlow::class;
    }
}
