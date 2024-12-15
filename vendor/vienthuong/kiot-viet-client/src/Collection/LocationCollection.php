<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\Location;

class LocationCollection extends Collection
{
    public function getModelClass(): string
    {
        return Location::class;
    }
}
