<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\InvoiceDetail;

class InvoiceDetailCollection extends Collection
{
    public function getModelClass(): string
    {
        return InvoiceDetail::class;
    }
}
