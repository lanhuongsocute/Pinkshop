<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

use VienThuong\KiotVietClient\Collection\PaymentCollection;

class Returns extends BaseModel
{
    /**
     * @var PaymentCollection|null
     */
    private $payments;

    /**
     * @var int|null
     */
    private $invoiceId;

    /**
     * @var string|null
     */
    private $returnDate;

    /**
     * @var int|null
     */
    private $branchId;

    /**
     * @var string|null
     */
    private $branchName;

    /**
     * @var int|null
     */
    private $receivedById;

    /**
     * @var string|null
     */
    private $soldByName;

    /**
     * @var int|null
     */
    private $customerId;

    /**
     * @var string|null
     */
    private $customerCode;

    /**
     * @var string|null
     */
    private $customerName;

    /**
     * @var float|null
     */
    private $returnTotal;

    /**
     * @var float|null
     */
    private $totalPayment;

    /**
     * @var int|null
     */
    private $status;

    /**
     * @var string|null
     */
    private $statusValue;

    /**
     * @var array|null
     */
    private $returnDetails;

    /**
     */
    public function getPayments(): ?PaymentCollection
    {
        return $this->payments;
    }

    /**
     */
    public function setPayments(?PaymentCollection $payments): void
    {
        $this->payments = $payments;
    }

    /**
     */
    public function getInvoiceId(): ?int
    {
        return $this->invoiceId;
    }

    /**
     */
    public function setInvoiceId(?int $invoiceId): void
    {
        $this->invoiceId = $invoiceId;
    }

    /**
     */
    public function getReturnDate(): ?string
    {
        return $this->returnDate;
    }

    /**
     */
    public function setReturnDate(?string $returnDate): void
    {
        $this->returnDate = $returnDate;
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
    public function getBranchName(): ?string
    {
        return $this->branchName;
    }

    /**
     */
    public function setBranchName(?string $branchName): void
    {
        $this->branchName = $branchName;
    }

    /**
     */
    public function getReceivedById(): ?int
    {
        return $this->receivedById;
    }

    /**
     */
    public function setReceivedById(?int $receivedById): void
    {
        $this->receivedById = $receivedById;
    }

    /**
     */
    public function getSoldByName(): ?string
    {
        return $this->soldByName;
    }

    /**
     */
    public function setSoldByName(?string $soldByName): void
    {
        $this->soldByName = $soldByName;
    }

    /**
     */
    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    /**
     */
    public function setCustomerId(?int $customerId): void
    {
        $this->customerId = $customerId;
    }

    /**
     */
    public function getCustomerCode(): ?string
    {
        return $this->customerCode;
    }

    /**
     */
    public function setCustomerCode(?string $customerCode): void
    {
        $this->customerCode = $customerCode;
    }

    /**
     */
    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    /**
     */
    public function setCustomerName(?string $customerName): void
    {
        $this->customerName = $customerName;
    }

    /**
     */
    public function getReturnTotal(): ?float
    {
        return $this->returnTotal;
    }

    /**
     */
    public function setReturnTotal(?float $returnTotal): void
    {
        $this->returnTotal = $returnTotal;
    }

    /**
     */
    public function getTotalPayment(): ?float
    {
        return $this->totalPayment;
    }

    /**
     */
    public function setTotalPayment(?float $totalPayment): void
    {
        $this->totalPayment = $totalPayment;
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
    public function getReturnDetails(): ?array
    {
        return $this->returnDetails;
    }

    /**
     */
    public function setReturnDetails(?array $returnDetails): void
    {
        $this->returnDetails = $returnDetails;
    }
}
