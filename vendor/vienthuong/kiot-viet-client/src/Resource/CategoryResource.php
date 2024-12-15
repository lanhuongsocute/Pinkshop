<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use VienThuong\KiotVietClient\Collection\CategoryCollection;
use VienThuong\KiotVietClient\Endpoint;
use VienThuong\KiotVietClient\Model\Category;

class CategoryResource extends BaseResource
{
    public function getEndPoint() : string
    {
        return Endpoint::CATEGORY_ENDPOINT;
    }

    public function getExpectedModel() : string
    {
        return Category::class;
    }

    public function getCollectionClass() : string
    {
        return CategoryCollection::class;
    }
}
