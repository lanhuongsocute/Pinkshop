<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use GuzzleHttp\Psr7\Request;
use VienThuong\KiotVietClient\Collection\CollectionInterface;
use VienThuong\KiotVietClient\Collection\ProductAttributeCollection;
use VienThuong\KiotVietClient\Collection\ProductCollection;
use VienThuong\KiotVietClient\Endpoint;
use VienThuong\KiotVietClient\Model\Product;
use VienThuong\KiotVietClient\Model\ProductAttribute;

class ProductResource extends BaseResource
{
    public function getEndPoint() : string
    {
        return Endpoint::PRODUCT_ENDPOINT;
    }

    public function getExpectedModel() : string
    {
        return Product::class;
    }

    public function getCollectionClass() : string
    {
        return ProductCollection::class;
    }

    public function getAllAttributes(array $headers = []) : ProductAttributeCollection
    {
        $request = new Request('GET', Endpoint::PRODUCT_ATTRIBUTES_ENDPOINT, $headers);

        return $this->client->execute(
            $request,
            ProductAttribute::class,
            ProductAttributeCollection::class
        )->parseResponse();
    }

    public function listAddProducts(CollectionInterface $productCollection, array $headers = [])
    {
        $request = new Request(
            'POST',
            Endpoint::PRODUCT_LIST_ADD_ENDPOINT,
            $headers,
            json_encode(array_filter([
                'listProducts' => $productCollection->toArray()
            ]))
        );

        return $this->client->execute($request)->parseResponse();
    }
}
