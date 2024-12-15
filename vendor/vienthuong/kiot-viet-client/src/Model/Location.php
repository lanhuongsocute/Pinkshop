<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

use VienThuong\KiotVietClient\Collection\DeliveryCollection;
use VienThuong\KiotVietClient\Collection\WardCollection;

class Location extends BaseModel
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $normalName;

    /**
     * @var DeliveryCollection|null
     */
    private $invoiceDeliveries;

    /**
     * @var WardCollection|null
     */
    private $wards;

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
    public function getNormalName(): ?string
    {
        return $this->normalName;
    }

    /**
     */
    public function setNormalName(?string $normalName): void
    {
        $this->normalName = $normalName;
    }

    /**
     */
    public function getInvoiceDeliveries(): ?DeliveryCollection
    {
        return $this->invoiceDeliveries;
    }

    /**
     */
    public function setInvoiceDeliveries(?DeliveryCollection $invoiceDeliveries): void
    {
        $this->invoiceDeliveries = $invoiceDeliveries;
    }

    /**
     */
    public function getWards(): ?WardCollection
    {
        return $this->wards;
    }

    /**
     */
    public function setWards(?WardCollection $wards): void
    {
        $this->wards = $wards;
    }
}
