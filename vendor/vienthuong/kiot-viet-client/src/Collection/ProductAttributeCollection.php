<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\ProductAttribute;

class ProductAttributeCollection extends Collection
{
    public function getModelClass(): string
    {
        return ProductAttribute::class;
    }

    public function transformItems(array $items) : array
    {
        $transformItems = [];

        foreach ($items as $item) {
            if (!empty($item['attributeValues'])) {
                foreach ($item['attributeValues'] as $attributeValue) {
                    $transformItems[] = [
                        'id' => $item['id'],
                        'attributeName' => $item['name'],
                        'attributeValue' => $attributeValue['value']
                    ];
                }
            }
        }

        return $transformItems;
    }
}
