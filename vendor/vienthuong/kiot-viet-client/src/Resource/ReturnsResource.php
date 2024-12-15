<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use VienThuong\KiotVietClient\Collection\ReturnsCollection;
use VienThuong\KiotVietClient\Endpoint;
use VienThuong\KiotVietClient\Model\Returns;

class ReturnsResource extends BaseResource
{
    public function getEndPoint() : string
    {
        return Endpoint::RETURN_ENDPOINT;
    }

    public function getExpectedModel() : string
    {
        return Returns::class;
    }

    public function getCollectionClass() : string
    {
        return ReturnsCollection::class;
    }
}
