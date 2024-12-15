<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use GuzzleHttp\Psr7\Request;
use VienThuong\KiotVietClient\Client;
use VienThuong\KiotVietClient\Collection\Collection;
use VienThuong\KiotVietClient\Collection\CollectionInterface;
use VienThuong\KiotVietClient\Criteria\Criteria;
use VienThuong\KiotVietClient\Model\BaseModel;

abstract class BaseResource
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getCollectionClass() : string
    {
        return Collection::class;
    }

    abstract public function getEndPoint() : string;

    abstract public function getExpectedModel() : string;

    public function getById(int $id, array $queries = [], array $headers = []) : BaseModel
    {
        $endpoint = sprintf('%s/%s?%s', $this->getEndPoint(), $id, http_build_query($queries));
        $request = new Request('GET', $endpoint, $headers);

        return $this->client->execute($request, $this->getExpectedModel())->parseResponse();
    }

    public function getByCode(string $code) : BaseModel
    {
        $endpoint = sprintf('%s/code/%s', $this->getEndPoint(), $code);
        $request = new Request('GET', $endpoint);

        return $this->client->execute($request, $this->getExpectedModel())->parseResponse();
    }

    public function search(Criteria $criteria) : CollectionInterface
    {
        return $this->list($criteria->getQueries());
    }

    public function list(array $queries = [], array $headers = []) : CollectionInterface
    {
        $endpoint = $this->getEndPoint() . '?' . http_build_query(array_filter($queries));
        $request = new Request('GET', $endpoint, $headers);

        $response = $this->client->execute($request, $this->getExpectedModel(), $this->getCollectionClass());

        return $response->parseResponse();
    }

    public function create(BaseModel $model, array $additionalParams = [], array $headers = []) : BaseModel
    {
        return $this->createRaw(array_merge($model->getModelData(), $additionalParams), $headers);
    }

    public function update(BaseModel $model, array $additionalParams = [], array $headers = []) : BaseModel
    {
        return $this->updateRaw($model->getId(), array_merge($model->getModelData(), $additionalParams), $headers);
    }

    public function remove(string $id, array $headers = [])
    {
        $endpoint = $this->getEndPoint() . "/" . $id;

        $request = new Request('DELETE', $endpoint, $headers);

        $response = $this->client->execute($request);

        return array_merge(['id' => $id, 'class' => $this->getExpectedModel()], $response->parseResponse());
    }

    public function createRaw(array $formBody = [], array $headers = []) : BaseModel
    {
        $endpoint = $this->getEndPoint();

        $request = new Request('POST', $endpoint, $headers, json_encode(array_filter($formBody)));

        return $this->client->execute($request, $this->getExpectedModel())->parseResponse();
    }

    public function updateRaw(?int $id, array $formBody = [], array $headers = []) : BaseModel
    {
        $endpoint = $this->getEndPoint() . "/" . $id;

        $request = new Request('PUT', $endpoint, $headers, json_encode(array_filter($formBody)));

        return $this->client->execute($request, $this->getExpectedModel())->parseResponse();
    }
}
