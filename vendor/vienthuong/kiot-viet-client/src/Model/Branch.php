<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

/**
 * Class Branch
 * @package VienThuong\KiotVietClient\Model
 *
 * Chi nhánh của cửa hàng
 */
class Branch extends BaseModel
{
    /**
     * @var string
     */
    private $branchName;

    /**
     * @var string
     */
    private $branchCode;

    /**
     * @var string
     */
    private $contactNumber;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $address;

    /**
     */
    public function getBranchName(): string
    {
        return $this->branchName;
    }

    /**
     */
    public function setBranchName(string $branchName): void
    {
        $this->branchName = $branchName;
    }

    /**
     */
    public function getBranchCode(): string
    {
        return $this->branchCode;
    }

    /**
     */
    public function setBranchCode(string $branchCode): void
    {
        $this->branchCode = $branchCode;
    }

    /**
     */
    public function getContactNumber(): string
    {
        return $this->contactNumber;
    }

    /**
     */
    public function setContactNumber(string $contactNumber): void
    {
        $this->contactNumber = $contactNumber;
    }

    /**
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }
}
