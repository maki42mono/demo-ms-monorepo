<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class LegionfarmApiService
{
    const URI_UPDATE_ORDER_APPLICATION = 'order-application/update';

    /** @var string */
    protected $apiUrl;

    /** @var CurlHttpClient */
    protected $client;

    public function __construct(string $apiUrl, string $apiToken)
    {
        $this->apiUrl = $apiUrl;
        $this->client = HttpClient::create([
            'auth_bearer' => $apiToken,
        ]);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $data
     * @return array
     */
    protected function request(string $method, string $url, array $data = []): array
    {
        $options = [];

        if ($data !== []) {
            $options['body'] = $data;
        }

        try {
            $result = $this->client->request($method, $url, $options);

            if ($result->getStatusCode() !== Response::HTTP_OK) {
                throw new BadRequestHttpException('Bad response from Legionfarm API');
            }

            $response = json_decode($result->getContent(), true);
        } catch (ExceptionInterface $exception) {
            throw new BadRequestHttpException('Cannot reach Legionfarm API');
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException('Cannot parse response from Legionfarm API');
        }

        if (!is_array($response)) {
            return [];
        }

        return $response;
    }

    /**
     * @param array $data
     * @return array
     */
    public function updateOrderApplication(array $data): array
    {
        return $this->request('POST', $this->apiUrl . self::URI_UPDATE_ORDER_APPLICATION);
    }
}
