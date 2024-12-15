<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use VienThuong\KiotVietClient\Collection\LocationCollection;
use VienThuong\KiotVietClient\Endpoint;
use VienThuong\KiotVietClient\Model\Location;

class LocationResource extends BaseResource
{
    public function getEndPoint() : string
    {
        return Endpoint::LOCATION_ENDPOINT;
    }

    public function getExpectedModel() : string
    {
        return Location::class;
    }

    public function getCollectionClass() : string
    {
        return LocationCollection::class;
    }
}
