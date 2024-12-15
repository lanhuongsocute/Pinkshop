<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

/**
 * Class Category
 * @package VienThuong\KiotVietClient\Model
 *
 * Nhóm hàng
 */
class Category extends BaseModel
{
    /**
     * @var int
     */
    private $categoryId;

    /**
     * @var int
     */
    private $parentId;

    /**
     * @var int
     */
    private $rank;

    /**
     * @var bool
     */
    private $isActive;

    /**
     * @var string
     */
    private $categoryName;

    /**
     * @var bool
     */
    private $hasChild;

    /**
     * @var array
     */
    private $children;

    /**
     * @return int
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    /**
     * @param  int  $categoryId
     */
    public function setCategoryId(?int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return int
     */
    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    /**
     * @param  int  $parentId
     */
    public function setParentId(?int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * @return string
     */
    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    /**
     * @param  string  $categoryName
     */
    public function setCategoryName(?string $categoryName): void
    {
        $this->categoryName = $categoryName;
    }

    /**
     * @return bool
     */
    public function getIsHasChild(): ?bool
    {
        return $this->hasChild;
    }

    /**
     * @param  bool  $hasChild
     */
    public function setHasChild(?bool $hasChild): void
    {
        $this->hasChild = $hasChild;
    }

    /**
     * @return array
     */
    public function getChildren(): ?array
    {
        return $this->children;
    }

    /**
     * @param  array  $children
     */
    public function setChildren(?array $children): void
    {
        $this->children = $children;
    }

    /**
     * @return int
     */
    public function getRank(): ?int
    {
        return $this->rank;
    }

    /**
     * @param  int  $rank
     */
    public function setRank(?int $rank): void
    {
        $this->rank = $rank;
    }

    /**
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param  bool  $isActive
     */
    public function setIsActive(?bool $isActive): void
    {
        $this->isActive = $isActive;
    }
}
