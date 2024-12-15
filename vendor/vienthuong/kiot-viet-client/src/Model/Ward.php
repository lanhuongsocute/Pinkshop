<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

use VienThuong\KiotVietClient\Collection\DeliveryCollection;

class Ward extends BaseModel
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $normalName;

    /**
     * @var int|null
     */
    private $parentId;

    /**
     */
    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    /**
     */
    public function setParentId(?int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     */
    public function getNormalName(): ?string
    {
        return $this->normalName;
    }

    /**
     */
    public function setNormalName(?string $normalName): void
    {
        $this->normalName = $normalName;
    }

    /**
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
