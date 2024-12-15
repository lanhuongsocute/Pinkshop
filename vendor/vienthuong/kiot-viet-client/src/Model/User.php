<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

/**
 * Class User
 * @package VienThuong\KiotVietClient\Model
 *
 * Trả lại danh sách toàn bộ người dùng của cửa hàng đã được xác nhận và không cho
thấy thông tin Super Admin (isAdmin = true).
 */
class User extends BaseModel
{
    /**
     * @var string|null
     */
    private $userName;

    /**
     * @var string|null
     */
    private $givenName;

    /**
     * @var string|null
     */
    private $address;

    /**
     * @var string|null
     */
    private $mobilePhone;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string|null
     */
    private $birthDate;

    /**
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     */
    public function setUserName(?string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     */
    public function getGivenName(): ?string
    {
        return $this->givenName;
    }

    /**
     */
    public function setGivenName(?string $givenName): void
    {
        $this->givenName = $givenName;
    }

    /**
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     */
    public function getMobilePhone(): ?string
    {
        return $this->mobilePhone;
    }

    /**
     */
    public function setMobilePhone(?string $mobilePhone): void
    {
        $this->mobilePhone = $mobilePhone;
    }

    /**
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     */
    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    /**
     */
    public function setBirthDate(?string $birthDate): void
    {
        $this->birthDate = $birthDate;
    }
}
