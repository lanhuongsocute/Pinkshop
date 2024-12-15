<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient;

use GuzzleHttp\Psr7\MessageTrait;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use VienThuong\KiotVietClient\Collection\Collection;
use VienThuong\KiotVietClient\Collection\CollectionInterface;
use VienThuong\KiotVietClient\Model\BaseModel;

class KiotVietResponse
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var string|null
     */
    private $expectedClass;

    /**
     * @var string|null
     */
    private $collectionClass;

    public function __construct(
        ResponseInterface $response,
        ?string $expectedClass = null,
        ?string $collectionClass = null
    ) {
        $this->response = $response;
        $this->expectedClass = $expectedClass;
        $this->collectionClass = $collectionClass;
        $this->parseResponse();
    }

    /**
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     */
    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    /**
     */
    public function getExpectedClass(): ?string
    {
        return $this->expectedClass;
    }

    /**
     */
    public function setExpectedClass(?string $expectedClass): void
    {
        $this->expectedClass = $expectedClass;
    }

    /**
     */
    public function getCollectionClass(): ?string
    {
        return $this->collectionClass;
    }

    /**
     */
    public function setCollectionClass(?string $collectionClass): void
    {
        $this->collectionClass = $collectionClass;
    }

    /**
     * @return array|CollectionInterface|BaseModel|mixed
     */
    public function parseResponse()
    {
        $body = json_decode((string) $this->response->getBody());

        $responseBody = $body->data ?? $body;
        $expectedModel = $this->expectedClass;
        $decodedBody = json_decode(json_encode($responseBody, JSON_FORCE_OBJECT), true);

        if ((int)$this->response->getStatusCode() >= 400) {
            return $decodedBody;
        }

        if ($expectedModel && class_exists($expectedModel) && isset($decodedBody)) {
            $expectedCollection = $this->collectionClass && class_exists($this->collectionClass)
                ? $this->collectionClass : Collection::class;

            if ((isset($body->data) && is_array($body->data)) || is_array($body)) {
                $collection = new $expectedCollection();
                $collection->setPageSize($body->pageSize ?? 0);
                $collection->setTotal($body->total ?? count($body->data ?? $body));
                $collection->setItems($decodedBody);
                $collection->setRemovedIds($body->removedIds ?? []);
                $collection->setTimestamp($body->timestamp ?? time());

                return $collection;
            }

            return new $expectedModel($decodedBody);
        }

        return (array) $body;
    }
}
