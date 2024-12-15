<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use GuzzleHttp\Psr7\Request;
use VienThuong\KiotVietClient\Collection\CustomerCollection;
use VienThuong\KiotVietClient\Endpoint;
use VienThuong\KiotVietClient\Model\Customer;

class CustomerResource extends BaseResource
{
    public function getEndPoint() : string
    {
        return Endpoint::CUSTOMER_ENDPOINT;
    }

    public function getExpectedModel() : string
    {
        return Customer::class;
    }

    public function getCollectionClass() : string
    {
        return CustomerCollection::class;
    }

    public function listAddCustomers(CustomerCollection $customerCollection, array $headers = [])
    {
        $request = new Request(
            'POST',
            Endpoint::CUSTOMER_LIST_ADD_ENDPOINT,
            $headers,
            json_encode(array_filter([
                'listCustomers' => [$customerCollection->toArray()]
            ]))
        );

        return $this->client->execute($request)->parseResponse();
    }
}
