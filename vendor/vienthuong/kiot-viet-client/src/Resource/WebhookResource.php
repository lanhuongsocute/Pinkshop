<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use GuzzleHttp\Psr7\Request;
use VienThuong\KiotVietClient\Collection\Collection;
use VienThuong\KiotVietClient\Endpoint;
use VienThuong\KiotVietClient\Model\BaseModel;
use VienThuong\KiotVietClient\Model\Webhook;

class WebhookResource extends BaseResource
{
    public function getEndPoint() : string
    {
        return Endpoint::WEBHOOK_ENDPOINT;
    }

    public function getExpectedModel() : string
    {
        return Webhook::class;
    }

    public function getCollectionClass() : string
    {
        return Collection::class;
    }

    /**
     * Đăng ký webhook
     *
     * @throws \VienThuong\KiotVietClient\Exception\KiotVietException
     *
     * Đăng kí webhook
     * @return array|mixed|\VienThuong\KiotVietClient\Collection\CollectionInterface|BaseModel
     */
    public function registerWebhook(
        string $type,
        string $url,
        bool $isActive,
        string $description,
        array $headers = []
    ) {
        $request = new Request('POST', $this->getEndPoint(), $headers, json_encode([
            'Type' => $type,
            'Url' => $url,
            'IsActive' => $isActive,
            'Description' => $description
        ]));

        return $this->client->execute(
            $request,
            $this->getExpectedModel(),
            $this->getCollectionClass()
        )->parseResponse();
    }

    /**
     * Huỷ đăng ký Webhook
     *
     * @throws \VienThuong\KiotVietClient\Exception\KiotVietException
     * @return array
     */
    public function removeWebhook(string $webhookId, array $headers = [])
    {
        $endpoint = $this->getEndPoint() . "/" . $webhookId;

        $request = new Request('DELETE', $endpoint, $headers);

        $response = $this->client->execute($request);

        return array_merge(['id' => $webhookId, 'class' => $this->getExpectedModel()], $response->parseResponse());
    }

    /**
     * Danh sách webhook
     *
     * @throws \VienThuong\KiotVietClient\Exception\KiotVietException
     */
    public function listWebhook(array $headers = [])
    {
        // Alias
        return $this->list($headers);
    }

    /**
     * Chi tiết webhook
     *
     * @param  string  $webhookId
     * @throws \VienThuong\KiotVietClient\Exception\KiotVietException
     */
    public function detailWebhook(int $webhookId, array $headers = [])
    {
        // Alias
        return $this->getById($webhookId, $headers);
    }
}
