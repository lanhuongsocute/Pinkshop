<?php

require_once(__DIR__ . "/auth.php");

use VienThuong\KiotVietClient;
use VienThuong\KiotVietClient\Model\Product;
use VienThuong\KiotVietClient\Criteria\ProductCriteria;
use VienThuong\KiotVietClient\Resource\BranchResource;
use VienThuong\KiotVietClient\Resource\CategoryResource;
use VienThuong\KiotVietClient\Resource\ProductResource;
use Vienthuong\KiotVietClient\Exception\KiotVietException;

$branchResource = new BranchResource($client);
$productResource = new ProductResource($client);
$categoryResource = new CategoryResource($client);

// Create some products
$product1 = new Product();
$product1->setName('NEW SP'.rand(1, 100000));
$product1->setCode('CODE_'.rand(1, 100000));
$product1->setCategoryName('CATEGORY '.rand(1, 100000));
$product1->setProductType(Product::PRODUCT_TYPE_NORMAL);

$product2 = new Product();
$product2->setName('NEW SP'.rand(1, 100000));
$product2->setCode('CODE_'.rand(1, 100000));
$product2->setProductType(Product::PRODUCT_TYPE_NORMAL);
$product2->setCategoryName('CATEGORY '.rand(1, 100000));


$product3 = new Product();
$product3->setName('NEW SP'.rand(1, 100000));
$product3->setCode('CODE_'.rand(1, 100000));
$product3->setProductType(Product::PRODUCT_TYPE_SERVICE);
$product3->setCategoryName('CATEGORY '.rand(1, 100000));


// Create a resource
$productResource->create($product1);
$productResource->create($product2);
$productResource->create($product3);

// List all product
$productList = $productResource->list();

$product = $productResource->getById($productList->first()->getId());

$productByCode = $productResource->getByCode($productList->first()->getCode());


// Search by criteria
$product = new Product();
$product->setProductType(Product::PRODUCT_TYPE_NORMAL);

$criteria = new ProductCriteria($product);

$criteria->setPageSize(40);
$criteria->addCriteria('name', $product->getName());
$products = $productResource->search($criteria);

// Filter on the result collection
$filterResult = $products->filter(function (Product $product) {
    return $product->getName() === 'Test SP83913';
});

// Or filter by property, this will have the same result
$filterByPropertyResult = $products->filterByProperty('name', $products);

// Get product attribute
$productResource->getAllAttributes();


$productBatch = new KiotVietClient\Collection\ProductCollection();
$productBatch->setItems([
    new Product([
        'name' => 'Product Example #' . time(),
        'code' => 'CODE_' . time(),
        'categoryName' => 'CATEGORY NAME ' . time(),
    ]),
    new Product([
        'name' => 'Product Example #' . time(),
        'code' => 'CODE_' . time(),
        'categoryName' => 'CATEGORY NAME ' . time(),
    ])
]);

// Batch update products using collection
$productResource->listAddProducts($productBatch);


$productToUpdate = $productList->first();

// Update a resource
$productToUpdate->setName('UPDATE SP.' . rand(1, 2000));
$updateProduct = $productResource->update($productToUpdate);

// Delete a resource
$productResource->remove($updateProduct->getId());



/** @var Product $newProduct */
$productList = $productResource->list();
var_dump("LIST");
var_dump($productList->count());
//dd($productList->toArray());
// Batch update products using collection
$newProduct = $productList->first();
$newProduct->setName('NEW SP'.rand(1, 100000));
$newProduct->setCode('TO'.rand(1, 100000));

// Create a resource
$newProduct = $productResource->create($newProduct);
var_dump("CREATE");
var_dump($newProduct->getName());

// Get a resource
$product = $productResource->getById($newProduct->getId());
var_dump("GET");
var_dump($product->getName());

// Update a resource
$product->setName('UPDATE SP.' . rand(1, 2000));
$updateProduct = $productResource->update($product);
var_dump("UPDATE");
var_dump($updateProduct->getName());

// Delete a resource
$remove = $productResource->remove($updateProduct->getId());
var_dump("REMOVE");
var_dump($remove);

// Get delete resource
try {
    $result = $productResource->getById($updateProduct->getId());
} catch (KiotVietClient\Exception\KiotVietException $exception) {
    var_dump("This should be 420 because the product is deleted");
    var_dump($exception->getResponse()->getResponse()->getStatusCode());
    var_dump($exception->getResponse()->parseResponse());
}

// Custom Resource
$customResource = new KiotVietClient\Resource\CustomResource($client);
$response = $customResource
    ->setExpectedModel(KiotVietClient\Model\Product::class)
    ->setCollectionClass(KiotVietClient\Collection\ProductCollection::class)
    ->setEndPoint(KiotVietClient\Endpoint::PRODUCT_ENDPOINT)->list()->first();

var_dump("Example use custom resource");
var_dump($response->getName());
