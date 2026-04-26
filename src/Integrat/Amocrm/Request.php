<?php

namespace Integrat\Amocrm;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Request
{
    private Client $httpClient;

    public function __construct(string $domain, string $apiKey)
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://' . $domain . '/api/v4/',
            'timeout'  => 30,
            'connect_timeout' => 10,
            'verify' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'http_errors' => false,
            'debug' => true,
        ]);
    }

    private function send(string $method, string $endpoint, array $data = []): ?array
    {
        $options = [];
        if (!empty($data)) {
            $options['json'] = $data;
        }

        for ($i = 0; $i < 3; $i++) {
            try {
                $response = $this->httpClient->request($method, $endpoint, $options);
                $statusCode = $response->getStatusCode();

                // Обработка 404
                if ($statusCode === 404) {
                    return [];
                }

                // Успешный ответ (2xx)
                $body = $response->getBody()->getContents();
                return json_decode($body, true);

            } catch (TransferException $e) {
                if ($i === 2) {
                    // Это была последняя попытка
                    throw new \Exception(
                        "Было сделано 3 попытки к API amoCRM, которые закончились неудачей: {$method} {$endpoint}. "
                        . "Ошибка: " . $e
                    );
                }
                sleep(2);
            }
        }

        // Этот код не должен выполняться, но на всякий случай
        throw new \Exception("Неизвестная ошибка при запросе: {$method} {$endpoint}");
    }

    public function post(string $endpoint, array $data): ?array
    {
        return $this->send('POST', $endpoint, $data);
    }

    public function get(string $endpoint): ?array
    {
        return $this->send('GET', $endpoint);
    }

    public function patch(string $endpoint, array $data): ?array
    {
        return $this->send('PATCH', $endpoint, $data);
    }
}