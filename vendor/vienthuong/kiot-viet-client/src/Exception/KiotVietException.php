<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Exception;

use \Exception;
use Throwable;
use VienThuong\KiotVietClient\KiotVietResponse;

class KiotVietException extends Exception
{
    public function __construct($message = "", $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @var array
     */
    private $errors;

    /**
     * @var string
     */
    private $errorCode;

    /**
     * @var KiotVietResponse
     */
    private $response;

    /**
     * @return string
     */
    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    /**
     * @param  string  $errorCode
     */
    public function setErrorCode(?string $errorCode): void
    {
        $this->errorCode = $errorCode;
    }

    /**
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     */
    public function getResponse(): KiotVietResponse
    {
        return $this->response;
    }

    /**
     */
    public function setResponse(KiotVietResponse $response): void
    {
        $this->response = $response;
    }
}
