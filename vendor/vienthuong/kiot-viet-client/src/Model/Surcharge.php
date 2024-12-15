<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

/**
 * Class Surcharge
 * @package VienThuong\KiotVietClient\Model
 *
 * Trả lại toàn bộ danh sách thu khác của cửa hàng đã được xác nhận
 *
 * Chú ý: Hiện tại KiotViet hỗ trợ các thiết lập cho tính năng thu khác như sau:
Trong trường hợp người dùng không tích chọn setting cho “Hỗ trợ các khoản thu khác khi bán hàng”, khi
gọi các API danh sách thu khác, API sẽ trả lại thông báo exception “Chưa bật thu khác trong thiết lập cửa
hàng”.
 */
class Surcharge extends BaseModel
{
    /**
     * @var int|null
     */
    private $invoiceId;

    /**
     * @var int|null
     */
    private $surchargeId;

    /**
     * @var string|null
     */
    private $surchargeName;

    /**
     * @var float|null
     */
    private $surValue;

    /**
     * @var float|null
     */
    private $value;

    /**
     * @var float|null
     */
    private $valueRatio;

    /**
     */
    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     */
    public function setValue(?float $value): void
    {
        $this->value = $value;
    }

    /**
     */
    public function getValueRatio(): ?float
    {
        return $this->valueRatio;
    }

    /**
     */
    public function setValueRatio(?float $valueRatio): void
    {
        $this->valueRatio = $valueRatio;
    }

    /**
     * @var float|null
     */
    private $price;

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
    public function getSurchargeId(): ?int
    {
        return $this->surchargeId;
    }

    /**
     */
    public function setSurchargeId(?int $surchargeId): void
    {
        $this->surchargeId = $surchargeId;
    }

    /**
     */
    public function getSurchargeName(): ?string
    {
        return $this->surchargeName;
    }

    /**
     */
    public function setSurchargeName(?string $surchargeName): void
    {
        $this->surchargeName = $surchargeName;
    }

    /**
     */
    public function getSurValue(): ?float
    {
        return $this->surValue;
    }

    /**
     */
    public function setSurValue(?float $surValue): void
    {
        $this->surValue = $surValue;
    }

    /**
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     */
    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }
}
