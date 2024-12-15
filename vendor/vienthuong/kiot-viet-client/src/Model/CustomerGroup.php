<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

use VienThuong\KiotVietClient\Collection\CustomerGroupDetailCollection;

class CustomerGroup extends BaseModel
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var int|null
     */
    private $createdBy;

    /**
     * @var float|null
     */
    private $discount;

    /**
     * @var CustomerGroupDetailCollection
     */
    private $customerGroupDetails;
}
