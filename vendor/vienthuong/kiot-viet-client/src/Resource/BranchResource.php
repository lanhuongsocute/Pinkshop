<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use VienThuong\KiotVietClient\Collection\BranchCollection;
use VienThuong\KiotVietClient\Endpoint;
use VienThuong\KiotVietClient\Model\Branch;

class BranchResource extends BaseResource
{
    public function getEndPoint() : string
    {
        return Endpoint::BRANCH_ENDPOINT;
    }

    public function getExpectedModel() : string
    {
        return Branch::class;
    }

    public function getBranchCollection() : string
    {
        return BranchCollection::class;
    }
}
