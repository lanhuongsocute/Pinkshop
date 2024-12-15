<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\Invoice;

class InvoiceCollection extends Collection
{
    public function getModelClass(): string
    {
        return Invoice::class;
    }
}
