<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

class Delivery extends BaseModel
{
    public const INVOICE_DELIVERY_STATUSES = [
        1 => "Chờ xử lý",
        2 => "Đang giao hàng",
        3 => "Giao thành công",
        4 => "Đã chuyển hoàn",
        5 => "Đã hủy",
        6 => "Đang lấy hàng",
        7 => "Đã lấy hàng",
        8 => "Chờ lấy lại",
        9 => "Đã lấy hàng",
        10 => "Chờ giao lại",
        11 => "Chờ chuyển hàng",
        12 => "Chờ chuyển hoàn lại"
    ];

    /**
     * @var int|null
     */
    private $locationId;

    /**
     * @var string|null
     */
    private $locationName;

    /**
     * @var int|null
     */
    private $type;

    /**
     * @var float|null
     */
    private $price;

    /**
     * @var string|null
     */
    private $deliveryCode;

    /**
     * @var string|null
     */
    private $receiver;

    /**
     * @var string|null
     */
    private $address;

    /**
     * @var float|null
     */
    private $weight;

    /**
     * @var float|null
     */
    private $length;

    /**
     * @var float|null
     */
    private $width;

    /**
     * @var float|null
     */
    private $height;

    /**
     * @var int|null
     */
    private $partnerDeliveryId;

    /**
     * @var PartnerDelivery
     */
    private $partnerDelivery;

    /**
     */
    public function getLocationId(): ?int
    {
        return $this->locationId;
    }

    /**
     */
    public function setLocationId(?int $locationId): void
    {
        $this->locationId = $locationId;
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
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     */
    public function setType(?int $type): void
    {
        $this->type = $type;
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

    /**
     */
    public function getDeliveryCode(): ?string
    {
        return $this->deliveryCode;
    }

    /**
     */
    public function setDeliveryCode(?string $deliveryCode): void
    {
        $this->deliveryCode = $deliveryCode;
    }

    /**
     */
    public function getReceiver(): ?string
    {
        return $this->receiver;
    }

    /**
     */
    public function setReceiver(?string $receiver): void
    {
        $this->receiver = $receiver;
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
    public function getWeight(): ?float
    {
        return $this->weight;
    }

    /**
     */
    public function setWeight(?float $weight): void
    {
        $this->weight = $weight;
    }

    /**
     */
    public function getLength(): ?float
    {
        return $this->length;
    }

    /**
     */
    public function setLength(?float $length): void
    {
        $this->length = $length;
    }

    /**
     */
    public function getWidth(): ?float
    {
        return $this->width;
    }

    /**
     */
    public function setWidth(?float $width): void
    {
        $this->width = $width;
    }

    /**
     */
    public function getHeight(): ?float
    {
        return $this->height;
    }

    /**
     */
    public function setHeight(?float $height): void
    {
        $this->height = $height;
    }

    /**
     */
    public function getPartnerDeliveryId(): ?int
    {
        return $this->partnerDeliveryId;
    }

    /**
     */
    public function setPartnerDeliveryId(?int $partnerDeliveryId): void
    {
        $this->partnerDeliveryId = $partnerDeliveryId;
    }

    /**
     */
    public function getPartnerDelivery(): PartnerDelivery
    {
        return $this->partnerDelivery;
    }

    /**
     */
    public function setPartnerDelivery(PartnerDelivery $partnerDelivery): void
    {
        $this->partnerDelivery = $partnerDelivery;
    }
}
