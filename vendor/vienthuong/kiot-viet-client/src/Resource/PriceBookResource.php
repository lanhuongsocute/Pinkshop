<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use VienThuong\KiotVietClient\Collection\PriceBookCollection;
use VienThuong\KiotVietClient\Endpoint;
use VienThuong\KiotVietClient\Model\PriceBook;

class PriceBookResource extends BaseResource
{
    public function getEndPoint() : string
    {
        return Endpoint::PRICE_BOOK_ENDPOINT;
    }

    public function getExpectedModel() : string
    {
        return PriceBook::class;
    }

    public function getCollectionClass() : string
    {
        return PriceBookCollection::class;
    }
}
