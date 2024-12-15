<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use VienThuong\KiotVietClient\Collection\SaleChannelCollection;
use VienThuong\KiotVietClient\Endpoint;
use VienThuong\KiotVietClient\Model\SaleChannel;

class SaleChannelResource extends BaseResource
{
    public function getEndPoint() : string
    {
        return Endpoint::SALE_CHANNEL_ENDPOINT;
    }

    public function getExpectedModel() : string
    {
        return SaleChannel::class;
    }

    public function getCollectionClass() : string
    {
        return SaleChannelCollection::class;
    }
}
