<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use VienThuong\KiotVietClient\Collection\InvoiceCollection;
use VienThuong\KiotVietClient\Endpoint;
use VienThuong\KiotVietClient\Model\Invoice;

class InvoiceResource extends BaseResource
{
    public function getEndPoint() : string
    {
        return Endpoint::INVOICE_ENDPOINT;
    }

    public function getExpectedModel() : string
    {
        return Invoice::class;
    }

    public function getCollectionClass() : string
    {
        return InvoiceCollection::class;
    }
}
