<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\SaleChannel;

class SaleChannelCollection extends Collection
{
    public function getModelClass(): string
    {
        return SaleChannel::class;
    }
}
