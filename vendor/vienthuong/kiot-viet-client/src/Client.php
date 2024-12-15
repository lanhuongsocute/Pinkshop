<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient;

use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Message\ResponseInterface;
use VienThuong\KiotVietClient\Exception\KiotVietException;
use \GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;
use VienThuong\KiotVietClient\Collection\Collection;
use VienThuong\KiotVietClient\Utils\CreateFromTrait;

class Client
{
    const SCOPES = 'PublicApi.Access';

    private static $headers = [
        'Content-Type' => 'application/x-www-form-urlencoded'
    ];

    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var callable
     */
    private $tokenCallback;

    /**
     * @var int
     */
    private $expiresAt;

    /**
     * @var string
     */
    private $retailerCode;

    /**
     * @var string
     */
    private $retailerId;

    /**
     * @var string
     */
    private $userId;

    /**
     * @var array
     */
    private $config;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(string $clientId, string $clientSecret, array $config = [])
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->config = array_merge([
            'sec_before_refresh' => 30,
            'max_attempt' => 1,
            'sec_before_attempt' => 1,
        ], $config);

        $this->httpClient = $this->httpClient ?? $this->createHttpClient();
    }

    public function createHttpClient()
    {
        $handlerStack = HandlerStack::create(new CurlHandler());
        $handlerStack->push(Middleware::retry(function (
            $retries,
            RequestInterface $request,
            ?Response $response = null,
            ?RequestException $exception = null
        ) {
            // Limit the number of retries to 5
            if ($retries >= $this->config['max_attempt']) {
                return false;
            }

            // Retry connection exceptions
            if ($exception instanceof ConnectException) {
                return true;
            }

            if ($response && $response->getStatusCode() >= 500) {
                // Retry on server errors
                return true;
            }

            if ($this->logger) {
                $this->logger->warning(sprintf(
                    'Retrying %s %s %s/%s, %s',
                    $request->getMethod(),
                    $request->getUri(),
                    $retries + 1,
                    $this->config['max_attempt'],
                    $response ? 'status code: ' . $response->getStatusCode() : $exception->getMessage()
                ), [$request->getHeader('Host')[0]]);
            }

            return false;
        }, function ($numberOfRetries) {
            return $this->config['sec_before_attempt'] * 1000 * $numberOfRetries;
        }));

        return $this->httpClient = new HttpClient([
            'handler' => $handlerStack,
        ]);
    }

    /**
     */
    public function getHttpClient(): HttpClient
    {
        return $this->httpClient;
    }

    public function fetchAccessToken(): array
    {
        $response = $this->httpClient->post(Endpoint::AUTH_ENDPOINT, [
            'headers' => self::$headers,
            'form_params' => [
                'scopes' => self::SCOPES,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'client_credentials',
            ]
        ])->getBody()->getContents();

        $tokenPayload = json_decode($response, true);

        $this->setAccessToken($tokenPayload['access_token']);

        return $tokenPayload;
    }

    public function setLogger(LoggerInterface $logger) : self
    {
        $this->logger = $logger;
    }

    public function setTokenCallback(callable $callback): void
    {
        $this->tokenCallback = $callback;
    }

    public function refreshToken() : self
    {
        $response = $this->fetchAccessToken();

        $callback = $this->tokenCallback;

        if ($callback && is_callable($callback)) {
            $callback($response);
        }

        if ($this->logger) {
            $this->logger->info(sprintf(
                'Token is Refreshed at %s, clientId: %s, new Token: %s',
                time(),
                $this->clientId,
                json_encode($response)
            ));
        }

        return $this;
    }

    public function setAccessToken(string $accessToken) : self
    {
        $this->extractPayload($accessToken);

        if ($this->isTokenExpired()) {
            if ($this->logger) {
                $this->logger->warning(sprintf(
                    'Token is Expired at %s, try to refresh, userId: %s, retailerCode %s, retailerId %s, clientId %s',
                    time(),
                    $this->getUserId(),
                    $this->getRetailerCode(),
                    $this->getRetailerId(),
                    $this->clientId
                ));
            }

            return $this->refreshToken();
        }

        $this->accessToken = $accessToken;

        return $this;
    }

    public function extractPayload(string $accessToken)
    {
        $payload = json_decode(base64_decode(explode('.', $accessToken)[1]), true);

        $this->expiresAt = $payload['exp'];
        $this->retailerCode = $payload['client_RetailerCode'];
        $this->userId = $payload['client_UserId'];
        $this->retailerId = $payload['client_RetailerId'];

        return $payload;
    }

    /**
     */
    public function getRetailerCode(): string
    {
        return $this->retailerCode;
    }

    /**
     */
    public function getRetailerId(): string
    {
        return $this->retailerId;
    }

    /**
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    public function isTokenExpired() : bool
    {
        return $this->getExpiresAt() + $this->config['sec_before_refresh'] * 1000 < time();
    }

    /**
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     */
    public function getExpiresAt(): int
    {
        return $this->expiresAt;
    }

    /**
     */
    public function setExpiresAt(int $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    /**
     * Helper method to execute deferred HTTP requests.
     * @param  null  $expectedClass
     * @throws KiotVietException
     */
    public function execute(
        RequestInterface $request,
        $expectedClass = null,
        ?string $collectionClass = null
    ): KiotVietResponse {
        $request = $request
            ->withHeader('Accept', 'application/json')
            ->withHeader('Authorization', 'Bearer ' . $this->getAccessToken())
            ->withHeader('Retailer', $this->getRetailerCode())
            ->withHeader('Content-Type', 'application/json');

        $client = $this->getHttpClient();

        try {
            $response = $client->send($request);
        } catch (BadResponseException $exception) {
            if ($response = $exception->getResponse()) {
                $responseContent = json_decode($response->getBody()->getContents(), true);
                $kvException = new KiotVietException(
                    $responseContent['responseStatus']['message'] ?? $exception->getMessage(),
                    $response->getStatusCode()
                );
                $kvException->setErrorCode($responseContent['responseStatus']['errorCode'] ?? null);
                $kvException->setErrors($responseContent['responseStatus']['errors'] ?? []);
                $kvException->setResponse(new KiotVietResponse($response, $expectedClass, $collectionClass));

                throw $kvException;
            }
        }

        return new KiotVietResponse($response, $expectedClass, $collectionClass);
    }
}
