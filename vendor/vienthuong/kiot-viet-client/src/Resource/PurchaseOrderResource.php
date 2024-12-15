<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use VienThuong\KiotVietClient\Collection\CashFlowCollection;
use VienThuong\KiotVietClient\Collection\PurchaseOrderCollection;
use VienThuong\KiotVietClient\Endpoint;
use VienThuong\KiotVietClient\Model\CashFlow;
use VienThuong\KiotVietClient\Model\PurchaseOrder;

class PurchaseOrderResource extends BaseResource
{
    public function getEndPoint() : string
    {
        return Endpoint::PURCHASE_ORDER_ENDPOINT;
    }

    public function getExpectedModel() : string
    {
        return PurchaseOrder::class;
    }

    public function getCollectionClass() : string
    {
        return PurchaseOrderCollection::class;
    }
}
