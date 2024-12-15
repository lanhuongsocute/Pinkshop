<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

class PartnerDelivery extends BaseModel
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $address;

    /**
     * @var string|null
     */
    private $contactNumber;

    /**
     * @var string|null
     */
    private $email;

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
    public function getContactNumber(): ?string
    {
        return $this->contactNumber;
    }

    /**
     */
    public function setContactNumber(?string $contactNumber): void
    {
        $this->contactNumber = $contactNumber;
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
}
