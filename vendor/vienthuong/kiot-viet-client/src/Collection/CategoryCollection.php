<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\Category;

class CategoryCollection extends Collection
{
    public function getModelClass(): string
    {
        return Category::class;
    }

    public function transformItems(array $items) : array
    {
        return array_map(function ($item) {
            $item['id'] = $item['categoryId'] ?? null;
            return $item;
        }, $items);
    }
}
