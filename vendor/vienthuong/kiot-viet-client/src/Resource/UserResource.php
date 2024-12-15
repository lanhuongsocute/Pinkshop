<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use VienThuong\KiotVietClient\Collection\UserCollection;
use VienThuong\KiotVietClient\Endpoint;
use VienThuong\KiotVietClient\Model\User;

class UserResource extends BaseResource
{
    public function getEndPoint() : string
    {
        return Endpoint::USER_ENDPOINT;
    }

    public function getExpectedModel() : string
    {
        return User::class;
    }

    public function getCollectionClass() : string
    {
        return UserCollection::class;
    }
}
