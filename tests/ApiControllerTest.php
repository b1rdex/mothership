<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function order_create_error_when_no_body(): void
    {
        $response = $this->request('POST', '/api/order/create');
        $this->assertSame(400, $response->getStatusCode(), $response->getContent());
        $this->assertSame(
            ['ok' => false, 'error' => 'No input json present'],
            \json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR)
        );
    }

    /**
     * @test
     */
    public function order_create_error_validate_body(): void
    {
        $response = $this->request('POST', '/api/order/create', ['worker' => 'biba', 'is_master' => true]);
        $this->assertSame(400, $response->getStatusCode(), $response->getContent());
        $this->assertSame(
            ['ok' => false, 'error' => [
                'pair' => 'This value should not be blank.',
                'volume' => 'This value should not be blank.',
                'operation' => 'This value should not be blank.',
            ]],
            \json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR)
        );
    }

    /**
     * @test
     */
    public function order_create_is_master_true_success(): void
    {
        $response = $this->request('POST', '/api/order/create', [
            'worker' => 'biba',
            'is_master' => true,
            'pair' => 'USDRUB',
            'volume' => '0.02',
            'operation' => 'sell',
        ]);
        $this->assertSame(201, $response->getStatusCode(), $response->getContent());
        $this->assertSame(
            ['ok' => true],
            \json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR)
        );
    }

    /**
     * @test
     */
    public function order_create_is_master_false_success(): void
    {
        $response = $this->request('POST', '/api/order/create', [
            'worker' => 'buba',
            'is_master' => false,
            'pair' => 'EURUSD',
            'volume' => '3.15',
            'operation' => 'buy',
        ]);
        $this->assertSame(201, $response->getStatusCode(), $response->getContent());
        $this->assertSame(
            ['ok' => true],
            \json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR)
        );
    }

    /**
     * @test
     */
    public function orders_list(): int
    {
        $response = $this->request('GET', '/api/orders');
        $this->assertSame(200, $response->getStatusCode(), $response->getContent());
        $json = \json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertIsArray($json);
        $this->assertArrayHasKey('ok', $json);
        $this->assertTrue($json['ok']);
        $this->assertArrayHasKey('data', $json);
        $this->assertIsArray($json['data']);

        $maxId = -1;
        foreach ($json['data'] as $item) {
            $this->assertArrayHasKey('id', $item);
            $maxId = max($maxId, (int) $item['id']);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('isMaster', $item);
            $this->assertArrayHasKey('worker', $item);
            $this->assertArrayHasKey('pair', $item);
            $this->assertArrayHasKey('volume', $item);
            $this->assertArrayHasKey('operation', $item);
        }

        return $maxId - 1;
    }

    /**
     * @test
     * @depends orders_list
     */
    public function orders_list_since(int $since): void
    {
        $response = $this->request('GET', '/api/orders/'.$since);
        $this->assertSame(200, $response->getStatusCode(), $response->getContent());
        $json = \json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertIsArray($json);
        $this->assertArrayHasKey('ok', $json);
        $this->assertTrue($json['ok']);
        $this->assertArrayHasKey('data', $json);
        $this->assertIsArray($json['data']);
        self::assertCount(1, $json['data']);

        $minId = \INF;
        foreach ($json['data'] as $item) {
            $this->assertArrayHasKey('id', $item);
            $minId = min($minId, (int) $item['id']);
        }
        self::assertGreaterThan($since, $minId);
    }

    private function request(string $method, string $uri, $content = null): Response
    {
        $client = static::createClient();

        $client->request(
            $method,
            $uri,
            [],
            [],
            [],
            $content ? \json_encode($content, JSON_THROW_ON_ERROR) : null
        );

        $response = $client->getResponse();
        $this->assertSame('application/json', $response->headers->get('content-type'), $response->getContent());

        return $response;
    }
}
