<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

use VienThuong\KiotVietClient\Collection\CustomerGroupDetailCollection;

/**
 * Class Customer
 * @package VienThuong\KiotVietClient\Model
 *
 * Khách hàng
 */
class Customer extends BaseModel
{
    const IS_MALE = true;
    const IS_FEMALE = false;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $contactNumber;

    /**
     * @var bool|null
     */
    private $gender;

    /**
     * @var string|null
     */
    private $birthDate;

    /**
     * @var string|null
     */
    private $address;

    /**
     * @var string|null
     */
    private $locationName;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $organization;

    /**
     * @var string|null
     */
    private $comment;

    /**
     * @var string|null
     */
    private $taxCode;

    /**
     * @var float|null
     */
    private $debt;

    /**
     * @var float|null
     */
    private $totalPoint;

    /**
     * @var float|null
     */
    private $totalRevenue;

    /**
     * @var float|null
     */
    private $rewardPoint;

    /**
     * @var float|null
     */
    private $totalInvoiced;

    /**
     * @var int|null
     */
    private $groupId;

    /**
     * @var string|null
     */
    private $groups;

    /**
     * @var array|null
     */
    private $groupIds;

    /**
     * @var CustomerGroupDetailCollection
     */
    private $customerGroupDetails;

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
    public function getGender(): ?bool
    {
        return $this->gender;
    }

    /**
     */
    public function setGender(?bool $gender): void
    {
        $this->gender = $gender;
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
    public function getLocationName(): ?string
    {
        return $this->locationName;
    }

    /**
     */
    public function setLocationName(?string $locationName): void
    {
        $this->locationName = $locationName;
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
    public function getOrganization(): ?string
    {
        return $this->organization;
    }

    /**
     */
    public function setOrganization(?string $organization): void
    {
        $this->organization = $organization;
    }

    /**
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     */
    public function getTaxCode(): ?string
    {
        return $this->taxCode;
    }

    /**
     */
    public function setTaxCode(?string $taxCode): void
    {
        $this->taxCode = $taxCode;
    }

    /**
     */
    public function getDebt(): ?float
    {
        return $this->debt;
    }

    /**
     */
    public function setDebt(?float $debt): void
    {
        $this->debt = $debt;
    }

    /**
     */
    public function getTotalPoint(): ?float
    {
        return $this->totalPoint;
    }

    /**
     */
    public function setTotalPoint(?float $totalPoint): void
    {
        $this->totalPoint = $totalPoint;
    }

    /**
     */
    public function getTotalRevenue(): ?float
    {
        return $this->totalRevenue;
    }

    /**
     */
    public function setTotalRevenue(?float $totalRevenue): void
    {
        $this->totalRevenue = $totalRevenue;
    }

    /**
     */
    public function getRewardPoint(): ?float
    {
        return $this->rewardPoint;
    }

    /**
     */
    public function setRewardPoint(?float $rewardPoint): void
    {
        $this->rewardPoint = $rewardPoint;
    }

    /**
     */
    public function getTotalInvoiced(): ?float
    {
        return $this->totalInvoiced;
    }

    /**
     */
    public function setTotalInvoiced(?float $totalInvoiced): void
    {
        $this->totalInvoiced = $totalInvoiced;
    }

    /**
     */
    public function getGroupId(): ?int
    {
        return $this->groupId;
    }

    /**
     */
    public function setGroupId(?int $groupId): void
    {
        $this->groupId = $groupId;
    }

    /**
     */
    public function getGroups(): ?string
    {
        return $this->groups;
    }

    /**
     */
    public function setGroups(?string $groups): void
    {
        $this->groups = $groups;
    }

    /**
     */
    public function getGroupIds(): ?array
    {
        return $this->groupIds;
    }

    /**
     */
    public function setGroupIds(?array $groupIds): void
    {
        $this->groupIds = $groupIds;
    }

    /**
     */
    public function getCustomerGroupDetails(): CustomerGroupDetailCollection
    {
        return $this->customerGroupDetails;
    }

    /**
     */
    public function setCustomerGroupDetails(CustomerGroupDetailCollection $customerGroupDetails): void
    {
        $this->customerGroupDetails = $customerGroupDetails;
    }
}
