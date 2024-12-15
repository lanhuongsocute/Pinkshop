<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use VienThuong\KiotVietClient\Endpoint;
use VienThuong\KiotVietClient\Model\BankAccount;

class BankAccountResource extends BaseResource
{
    public function getEndPoint() : string
    {
        return Endpoint::BANK_ACCOUNT_ENDPOINT;
    }

    public function getExpectedModel() : string
    {
        return BankAccount::class;
    }
}
