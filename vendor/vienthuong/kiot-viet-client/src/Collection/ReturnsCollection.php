<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\Returns;

class ReturnsCollection extends Collection
{
    public function getModelClass(): string
    {
        return Returns::class;
    }
}
