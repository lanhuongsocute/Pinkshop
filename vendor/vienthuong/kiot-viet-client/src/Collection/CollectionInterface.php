<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Collection;

use VienThuong\KiotVietClient\Model\BaseModel;

interface CollectionInterface
{
    public function add(BaseModel ...$items);

    public function append($value);

    public function toArray(): array;

    public function getModelClass(): string;

    public function getTotal(): int;

    public function setTotal(int $total): void;

    public function getPageSize(): int;

    public function setPageSize(int $pageSize): void;

    public function filter(\Closure $closure): ?self;

    public function filterByProperty(string $propertyName, $value): ?self;

    public function first(): ?BaseModel;

    public function last(): ?BaseModel;

    public function all(): ?array;

    public function count();
}
