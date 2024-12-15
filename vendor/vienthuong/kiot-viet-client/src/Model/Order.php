<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

use VienThuong\KiotVietClient\Collection\SurchargeCollection;
use VienThuong\KiotVietClient\Collection\OrderDetailCollection;
use VienThuong\KiotVietClient\Collection\PaymentCollection;

/**
 * Class Order
 * @package VienThuong\KiotVietClient\Model
 *
 * Đặt hàng
 */
class Order extends BaseModel
{
    /**
     * @var string
     */
    private $purchaseDate;

    /**
     * @var int
     */
    private $branchId;

    /**
     * @var string|null
     */
    private $branchName;

    /**
     * @var int|null
     */
    private $soldById;

    /**
     * @var int|null
     */
    private $cashierId;

    /**
     * @var int|null
     */
    private $accountId;

    /**
     * @var int|null
     */
    private $saleChannelId;

    /**
     * @var bool|null
     */
    private $makeInvoice;

    /**
     * @var Customer|null
     */
    private $customer;

    /**
     * @var string|null
     */
    private $soldByName;

    /**
     * @var float|null
     */
    private $total;

    /**
     * @var float|null
     */
    private $totalPayment;

    /**
     * @var float|null
     */
    private $discountRatio;

    /**
     * @var float|null
     */
    private $discount;

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
     * @var PaymentCollection
     */
    private $payments;

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
     * @var OrderDetailCollection
     */
    private $orderDetails;

    /**
     * @var SurchargeCollection
     */
    private $invoiceOrderSurcharges;

    /**
     * @var SurchargeCollection
     */
    private $surchages;

    /**
     * @var Delivery
     */
    private $orderDelivery;

    /**
     */
    public function getCashierId(): ?int
    {
        return $this->cashierId;
    }

    /**
     */
    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    /**
     */
    public function getSaleChannelId(): ?int
    {
        return $this->saleChannelId;
    }

    /**
     */
    public function getMakeInvoice(): ?bool
    {
        return $this->makeInvoice;
    }

    /**
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
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

    /**
     */
    public function getOrderDetails(): OrderDetailCollection
    {
        return $this->orderDetails;
    }

    /**
     */
    public function setOrderDetails(OrderDetailCollection $orderDetails): void
    {
        $this->orderDetails = $orderDetails;
    }

    /**
     * @return SurchargeCollection
     */
    public function getInvoiceOrderSurcharges(): ?SurchargeCollection
    {
        return $this->invoiceOrderSurcharges ?? $this->surchages;
    }

    /**
     * @param  SurchargeCollection  $invoiceOrderSurcharges
     */
    public function setInvoiceOrderSurcharges(?SurchargeCollection $invoiceOrderSurcharges): void
    {
        $this->invoiceOrderSurcharges = $invoiceOrderSurcharges;
        $this->surchages = $invoiceOrderSurcharges;
    }

    /**
     */
    public function getPurchaseDate(): string
    {
        return $this->purchaseDate;
    }

    /**
     */
    public function setPurchaseDate(string $purchaseDate): void
    {
        $this->purchaseDate = $purchaseDate;
    }

    /**
     */
    public function getBranchId(): int
    {
        return $this->branchId;
    }

    /**
     */
    public function setBranchId(int $branchId): void
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
    public function getSoldById(): ?int
    {
        return $this->soldById;
    }

    /**
     */
    public function setSoldById(?int $soldById): void
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
    public function getDiscountRatio(): ?float
    {
        return $this->discountRatio;
    }

    /**
     */
    public function setDiscountRatio(?float $discountRatio): void
    {
        $this->discountRatio = $discountRatio;
    }

    /**
     */
    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    /**
     */
    public function setDiscount(?float $discount): void
    {
        $this->discount = $discount;
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
     * @return PaymentCollection
     */
    public function getPayments(): ?PaymentCollection
    {
        return $this->payments;
    }

    /**
     * @param  PaymentCollection  $payments
     */
    public function setPayments(?PaymentCollection $payments): void
    {
        $this->payments = $payments;
    }
}
