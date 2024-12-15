<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

class Payment extends BaseModel
{
    /**
     * @var float|null
     */
    private $amount;

    /**
     * @var string|null
     */
    private $method;

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
    private $bankAccount;

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
    public function getBankAccount(): ?string
    {
        return $this->bankAccount;
    }

    /**
     */
    public function setBankAccount(?string $bankAccount): void
    {
        $this->bankAccount = $bankAccount;
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
     * @var string|null
     */
    private $transDate;

    /**
     * @var int|null
     */
    private $accountId;
}
