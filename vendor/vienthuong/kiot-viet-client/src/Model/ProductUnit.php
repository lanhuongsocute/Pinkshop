<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

class ProductUnit extends BaseModel
{
    /**
     * @var string;
     */
    private $name;

    /**
     * @var string
     */
    private $fullName;

    /**
     * @var int
     */
    private $conversionValue;

    /**
     */
    private $unit;

    /**
     * @var float
     */
    private $basePrice;

    /**
     * @var bool
     */
    private $allowssale;

    /**
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     */
    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     */
    public function getConversionValue(): int
    {
        return $this->conversionValue;
    }

    /**
     */
    public function setConversionValue(int $conversionValue): void
    {
        $this->conversionValue = $conversionValue;
    }

    /**
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     */
    public function setUnit($unit): void
    {
        $this->unit = $unit;
    }

    /**
     */
    public function getBasePrice(): float
    {
        return $this->basePrice;
    }

    /**
     */
    public function setBasePrice(float $basePrice): void
    {
        $this->basePrice = $basePrice;
    }

    /**
     */
    public function getIsAllowssale(): bool
    {
        return $this->allowssale;
    }

    /**
     */
    public function setAllowssale(bool $allowssale): void
    {
        $this->allowssale = $allowssale;
    }
}
