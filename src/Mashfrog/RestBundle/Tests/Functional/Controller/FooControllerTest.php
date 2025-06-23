<?php declare(strict_types=1);

namespace App\Darce\RestBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FooControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testFooAction(): void
    {
        $body = '{"bar":"baz"}';

        $this->client->request(
            'POST',
            '/api/foo',
            [],
            [],
            ['HTTP_Accept-Language' => 'it'],
            $body
        );

        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $jsonContent = json_decode($content, true);

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInternalType('string', $content);
        $this->assertTrue(array_key_exists('translated_string', $jsonContent));
        $this->assertEquals('Ciao baz', $jsonContent['translated_string']);
    }

    public function testFooActionInEnglish(): void
    {
        $body = '{"bar":"baz"}';

        $this->client->request(
            'POST',
            '/api/foo',
            [],
            [],
            ['HTTP_Accept-Language' => 'en'],
            $body
        );

        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $jsonContent = json_decode($content, true);

        $this->assertNotNull($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInternalType('string', $content);
        $this->assertTrue(array_key_exists('translated_string', $jsonContent));
        $this->assertEquals('Hello baz', $jsonContent['translated_string']);
    }

    public function testDeserializationException(): void
    {
        $body = '{"barr":"baz"}';
        $this->client->request(
            'POST',
            '/api/foo',
            [],
            [],
            ['HTTP_Accept-Language' => 'en'],
            $body
        );

        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $jsonContent = json_decode($content, true);

        $this->assertNotNull($response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertTrue(array_key_exists('error', $jsonContent));
    }
}