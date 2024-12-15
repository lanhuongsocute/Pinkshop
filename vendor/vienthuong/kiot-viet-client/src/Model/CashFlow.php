<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

class CashFlow extends BaseModel
{
    /**
     * @var string|null
     */
    private $address;

    /**
     * @var int|null
     */
    private $branchId;

    /**
     * @var string|null
     */
    private $wardName;

    /**
     * @var string|null
     */
    private $contactNumber;

    /**
     * @var int|null
     */
    private $createdBy;

    /**
     * @var int|null
     */
    private $usedForFinancialReporting;

    /**
     * @var int|null
     */
    private $cashFlowGroupId;

    /**
     * @var string|null
     */
    private $method;

    /**
     * @var string|null
     */
    private $partnerType;

    /**
     * @var int|null
     */
    private $partnnerId;

    /**
     * @var int|null
     */
    private $status;

    /**
     * @var string|null
     */
    private $statusValue;

    /**
     * @var string|null
     */
    private $transDate;

    /**
     * @var float|null
     */
    private $amount;

    /**
     * @var string|null
     */
    private $partnerName;

    /**
     * @var string|null
     */
    private $user;

    /**
     * @var int|null
     */
    private $accountId;

    /**
     * @var string|null
     */
    private $description;

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
    public function getBranchId(): ?int
    {
        return $this->branchId;
    }

    /**
     */
    public function setBranchId(?int $branchId): void
    {
        $this->branchId = $branchId;
    }

    /**
     */
    public function getWardName(): ?string
    {
        return $this->wardName;
    }

    /**
     */
    public function setWardName(?string $wardName): void
    {
        $this->wardName = $wardName;
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
    public function getUsedForFinancialReporting(): ?int
    {
        return $this->usedForFinancialReporting;
    }

    /**
     */
    public function setUsedForFinancialReporting(?int $usedForFinancialReporting): void
    {
        $this->usedForFinancialReporting = $usedForFinancialReporting;
    }

    /**
     */
    public function getCashFlowGroupId(): ?int
    {
        return $this->cashFlowGroupId;
    }

    /**
     */
    public function setCashFlowGroupId(?int $cashFlowGroupId): void
    {
        $this->cashFlowGroupId = $cashFlowGroupId;
    }

    /**
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     */
    public function setMethod(?string $method): void
    {
        $this->method = $method;
    }

    /**
     */
    public function getPartnerType(): ?string
    {
        return $this->partnerType;
    }

    /**
     */
    public function setPartnerType(?string $partnerType): void
    {
        $this->partnerType = $partnerType;
    }

    /**
     */
    public function getPartnnerId(): ?int
    {
        return $this->partnnerId;
    }

    /**
     */
    public function setPartnnerId(?int $partnnerId): void
    {
        $this->partnnerId = $partnnerId;
    }

    /**
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     */
    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }

    /**
     */
    public function getStatusValue(): ?string
    {
        return $this->statusValue;
    }

    /**
     */
    public function setStatusValue(?string $statusValue): void
    {
        $this->statusValue = $statusValue;
    }

    /**
     */
    public function getTransDate(): ?string
    {
        return $this->transDate;
    }

    /**
     */
    public function setTransDate(?string $transDate): void
    {
        $this->transDate = $transDate;
    }

    /**
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     */
    public function setAmount(?float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     */
    public function getPartnerName(): ?string
    {
        return $this->partnerName;
    }

    /**
     */
    public function setPartnerName(?string $partnerName): void
    {
        $this->partnerName = $partnerName;
    }

    /**
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     */
    public function setUser(?string $user): void
    {
        $this->user = $user;
    }

    /**
     */
    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    /**
     */
    public function setAccountId(?int $accountId): void
    {
        $this->accountId = $accountId;
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
}
