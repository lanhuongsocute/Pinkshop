<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

class SaleChannel extends BaseModel
{
    /**
     * @var bool|null
     */
    private $isNotDelete;

    /**
     * @var int|null
     */
    private $position;

    /**
     * @var bool|null
     */
    private $isActivate;

    /**
     * @var int|null
     */
    private $createdBy;

    /**
     * @var string|null
     */
    private $name;

    /**
     */
    public function getIsNotDelete(): ?bool
    {
        return $this->isNotDelete;
    }

    /**
     */
    public function setIsNotDelete(?bool $isNotDelete): void
    {
        $this->isNotDelete = $isNotDelete;
    }

    /**
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     */
    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    /**
     */
    public function getIsActivate(): ?bool
    {
        return $this->isActivate;
    }

    /**
     */
    public function setIsActivate(?bool $isActivate): void
    {
        $this->isActivate = $isActivate;
    }

    /**
     */
    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    /**
     */
    public function setCreatedBy(?int $createdBy): void
    {
        $this->createdBy = $createdBy;
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
