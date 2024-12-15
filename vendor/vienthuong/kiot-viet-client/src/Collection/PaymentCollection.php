<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\Payment;

class PaymentCollection extends Collection
{
    public function getModelClass(): string
    {
        return Payment::class;
    }
}
