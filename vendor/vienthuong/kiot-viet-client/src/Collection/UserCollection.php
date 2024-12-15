<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\User;

class UserCollection extends Collection
{
    public function getModelClass(): string
    {
        return User::class;
    }
}
