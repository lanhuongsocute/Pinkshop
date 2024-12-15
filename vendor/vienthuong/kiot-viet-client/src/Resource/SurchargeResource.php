<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Resource;

use GuzzleHttp\Psr7\Request;
use VienThuong\KiotVietClient\Collection\SurchargeCollection;
use VienThuong\KiotVietClient\Endpoint;
use VienThuong\KiotVietClient\Model\Surcharge;

class SurchargeResource extends BaseResource
{
    public function getEndPoint() : string
    {
        return Endpoint::SURCHARGE_ENDPOINT;
    }

    public function getExpectedModel() : string
    {
        return Surcharge::class;
    }

    public function getCollectionClass() : string
    {
        return SurchargeCollection::class;
    }

    /**
     * Ngừng/cho phép hoạt động 1 thu khác
     */
    public function toggleActive(int $surchargeId, bool $isActive, array $headers = [])
    {
        $endPoint = $this->getEndPoint() . '/' . $surchargeId . '/activesurchage';

        $request = new Request('POST', $endPoint, $headers, json_encode([
            'isActive' => $isActive
        ]));

        return $this->client->execute(
            $request,
            $this->getExpectedModel(),
            $this->getCollectionClass()
        )->parseResponse();
    }
}
