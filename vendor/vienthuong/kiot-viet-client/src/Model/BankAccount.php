<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

/**
 * Class BankAccount
 * @package VienThuong\KiotVietClient\Model
 *
 * Tài khoản ngân hàng của cửa hàng
 */
class BankAccount extends BaseModel
{
    /**
     * @var string|null
     */
    private $bankName;

    /**
     * @var string|null
     */
    private $accountNumber;

    /**
     * @var string|null
     */
    private $description;

    /**
     */
    public function getBankName(): ?string
    {
        return $this->bankName;
    }

    /**
     */
    public function setBankName(?string $bankName): void
    {
        $this->bankName = $bankName;
    }

    /**
     */
    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    /**
     */
    public function setAccountNumber(?string $accountNumber): void
    {
        $this->accountNumber = $accountNumber;
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
