<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use VienThuong\KiotVietClient\Collection\OrderCollection;
use VienThuong\KiotVietClient\Endpoint;
use VienThuong\KiotVietClient\Model\Order;

class OrderResource extends BaseResource
{
    public function getEndPoint() : string
    {
        return Endpoint::ORDER_ENDPOINT;
    }

    public function getExpectedModel() : string
    {
        return Order::class;
    }

    public function getCollectionClass() : string
    {
        return OrderCollection::class;
    }
}
