<?php declare(strict_types=1);


namespace VienThuong\KiotVietClient\Model;

trait CommonFields
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $code;

    /**
     * @var string
     */
    protected $createdDate;

    /**
     * @var string
     */
    protected $modifiedDate;

    /**
     * @var int
     */
    protected $retailerId;

    /**
     * @var array
     */
    protected $otherProperties;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param  int  $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     */
    public function getOtherProperties(): ?array
    {
        return $this->otherProperties;
    }

    /**
     * @return string
     */
    public function getModifiedDate(): ?string
    {
        return $this->modifiedDate;
    }

    /**
     * @param  string  $modifiedDate
     */
    public function setModifiedDate(?string $modifiedDate): void
    {
        $this->modifiedDate = $modifiedDate;
    }

    /**
     * @return int
     */
    public function getRetailerId(): ?int
    {
        return $this->retailerId;
    }

    /**
     * @param  int  $retailerId
     */
    public function setRetailerId(?int $retailerId): void
    {
        $this->retailerId = $retailerId;
    }

    /**
     * @return string
     */
    public function getCreatedDate(): ?string
    {
        return $this->createdDate;
    }

    /**
     * @param  string  $createdDate
     */
    public function setCreatedDate(?string $createdDate): void
    {
        $this->createdDate = $createdDate;
    }

    /**
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param  string  $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }
}
