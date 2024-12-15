<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use VienThuong\KiotVietClient\Collection\CashFlowCollection;
use VienThuong\KiotVietClient\Collection\CustomerGroupCollection;
use VienThuong\KiotVietClient\Endpoint;
use VienThuong\KiotVietClient\Model\CashFlow;
use VienThuong\KiotVietClient\Model\CustomerGroup;

class CashFlowResource extends BaseResource
{
    public function getEndPoint() : string
    {
        return Endpoint::CASH_FLOW_ENDPOINT;
    }

    public function getExpectedModel() : string
    {
        return CashFlow::class;
    }

    public function getCollectionClass() : string
    {
        return CashFlowCollection::class;
    }
}
