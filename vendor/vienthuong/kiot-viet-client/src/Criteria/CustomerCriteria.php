<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Criteria;

class CustomerCriteria extends Criteria
{
    public function setCode(string $code): self
    {
        return $this->addCriteria('code', $code);
    }

    public function setName(string $name): self
    {
        return $this->addCriteria('name', $name);
    }

    public function setContactNumber(string $contactNumber): self
    {
        return $this->addCriteria('contactNumber', $contactNumber);
    }

    public function setBirthDate(string $birthDate): self
    {
        return $this->addCriteria('birthDate', $birthDate);
    }

    public function setGroupId(string $groupId): self
    {
        return $this->addCriteria('groupId', $groupId);
    }

    public function setIncludeCustomerGroup(bool $includeCustomerGroup): self
    {
        return $this->addCriteria('includeCustomerGroup', $includeCustomerGroup);
    }

    public function setIncludeTotal(bool $includeTotal): self
    {
        return $this->addCriteria('includeTotal', $includeTotal);
    }
}
