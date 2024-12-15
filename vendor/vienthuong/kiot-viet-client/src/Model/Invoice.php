<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

use VienThuong\KiotVietClient\Collection\PaymentCollection;
use VienThuong\KiotVietClient\Collection\SurchargeCollection;

/**
 * Class Invoice
 * @package VienThuong\KiotVietClient\Model
 *
 * Hoá đơn
 */
class Invoice extends BaseModel
{
    const STATUSES = [
        'COMPLETED' => 1,
        'CANCELLED' => 2,
        'ONGOING' => 3,
        'CANT_DELIVERY' => 5
    ];

    const DELIVERY_STATUSES = [
        'NOT_SHIP_YET' => 1,
        'SHIPPING' => 2,
        'SHIPPED' => 3,
        'RETURNING' => 4,
        'RETURNED' => 5,
        'CANCELLED' => 6
    ];

    const STATUSES_LABEL = [
        1 => 'Hoàn thành',
        2 => 'Đã Hủy',
        3 => 'Đang xử lý',
        5 => 'Không thể giao'
    ];

    const DELIVERY_STATUSES_LABEL = [
        1 => 'Chưa giao',
        2 => 'Đang giao',
        3 => 'Đã giao',
        4 => 'Đang hoàn',
        5 => 'Đã hoàn',
        6 => 'Đã hủy'
    ];

    /**
     * @var SaleChannel|null
     */
    private $saleChannel;

    /**
     * @var Delivery
     */
    private $invoiceDelivery;

    /**
     * @var InvoiceDetail|null
     */
    private $invoiceDetails;

    /**
     * @var PaymentCollection
     */
    private $payments;

    /**
     * @var string|null
     */
    private $purchaseDate;

    /**
     * @var int|null
     */
    private $branchId;

    /**
     * @var string|null
     */
    private $branchName;

    private $soldById;

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
    private $customerName;

    /**
     * @var string|null
     */
    private $customerCode;

    /**
     * @var float|null
     */
    private $total;

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
     * @var string|null
     */
    private $description;

    /**
     * @var bool|null
     */
    private $usingCod;

    /**
     * @var SurchargeCollection
     */
    private $surchages;

    /**
     * @var SurchargeCollection
     */
    private $invoiceOrderSurcharges;

    /**
     */
    public function getSaleChannel(): ?SaleChannel
    {
        return $this->saleChannel;
    }

    /**
     */
    public function setSaleChannel(?SaleChannel $saleChannel): void
    {
        $this->saleChannel = $saleChannel;
    }

    /**
     */
    public function getInvoiceDelivery(): Delivery
    {
        return $this->invoiceDelivery;
    }

    /**
     */
    public function setInvoiceDelivery(Delivery $invoiceDelivery): void
    {
        $this->invoiceDelivery = $invoiceDelivery;
    }

    /**
     */
    public function getInvoiceDetails(): ?InvoiceDetail
    {
        return $this->invoiceDetails;
    }

    /**
     */
    public function setInvoiceDetails(?InvoiceDetail $invoiceDetails): void
    {
        $this->invoiceDetails = $invoiceDetails;
    }

    /**
     */
    public function getPayments(): PaymentCollection
    {
        return $this->payments;
    }

    /**
     */
    public function setPayments(PaymentCollection $payments): void
    {
        $this->payments = $payments;
    }

    /**
     */
    public function getPurchaseDate(): ?string
    {
        return $this->purchaseDate;
    }

    /**
     */
    public function setPurchaseDate(?string $purchaseDate): void
    {
        $this->purchaseDate = $purchaseDate;
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
    public function getSoldById()
    {
        return $this->soldById;
    }

    /**
     */
    public function setSoldById($soldById): void
    {
        $this->soldById = $soldById;
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
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     */
    public function setTotal(?float $total): void
    {
        $this->total = $total;
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
    public function getUsingCod(): ?bool
    {
        return $this->usingCod;
    }

    /**
     */
    public function setUsingCod(?bool $usingCod): void
    {
        $this->usingCod = $usingCod;
    }

    /**
     */
    public function getInvoiceOrderSurcharges()
    {
        return $this->invoiceOrderSurcharges;
    }

    /**
     */
    public function setInvoiceOrderSurcharges($invoiceOrderSurcharges): void
    {
        $this->invoiceOrderSurcharges = $invoiceOrderSurcharges;
    }

    /**
     * @return SurchargeCollection
     */
    public function getSurchages(): ?SurchargeCollection
    {
        return $this->surchages ?? $this->invoiceOrderSurcharges;
    }

    /**
     * @return SurchargeCollection
     */
    public function setSurchages(?SurchargeCollection $surchages): void
    {
        $this->surchages = $surchages;
        $this->invoiceOrderSurcharges = $surchages;
    }
}
