<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use VienThuong\KiotVietClient\Collection\CustomerGroupCollection;
use VienThuong\KiotVietClient\Endpoint;
use VienThuong\KiotVietClient\Model\CustomerGroup;

class CustomerGroupResource extends BaseResource
{
    public function getEndPoint() : string
    {
        return Endpoint::CUSTOMER_GROUP_ENDPOINT;
    }

    public function getExpectedModel() : string
    {
        return CustomerGroup::class;
    }

    public function getCollectionClass() : string
    {
        return CustomerGroupCollection::class;
    }
}
