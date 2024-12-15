<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

class ProductBatchExpire extends BaseModel
{
    /**
     * @var int|null
     */
    private $productId;

    /**
     * @var string|null
     */
    private $batchName;

    /**
     * @var string|null
     */
    private $fullNameVirgule;

    /**
     * @var string|null
     */
    private $expireDate;
}
