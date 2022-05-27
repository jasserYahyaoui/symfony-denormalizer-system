<?php

/**
 * Created by PhpStorm.
 * User: jasser
 * Date: 27/05/22
 * Time: 15:52
 */

namespace App\ApiManager;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class ApiManager.
 * The options are used mainly for Guzzle and the context are used for data normalization.
 *
 * @method mixed|ResponseInterface get(string $uri, array $options = [], array $context = [], string $type = null)
 * @method mixed|ResponseInterface head(string $uri, array $options = [])
 * @method mixed|ResponseInterface put(string $uri, array $options = [], array $context = [], string $type = null)
 * @method mixed|ResponseInterface post(string $uri, array $options = [], array $context = [], string $type = null)
 * @method mixed|ResponseInterface patch(string $uri, array $options = [])
 * @method mixed|ResponseInterface delete(string $uri, array $options = [], array $context = [], string $type = null)
 */
class ApiManager
{
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ApiManager constructor.
     *
     * @param HttpClientInterface $client
     * @param SerializerInterface $serializer
     */
    public function __construct(HttpClientInterface $client, SerializerInterface $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    /**
     * @param string $method
     * @param array $args
     *
     * @return array|object|ResponseInterface|PromiseInterface
     *
     * @throws \InvalidArgumentException
     */
    public function __call(string $method, array $args)
    {
        if (\count($args) < 1) {
            throw new \InvalidArgumentException('URI is required for api request.');
        }

        if (!\in_array($method, ['head', 'get', 'put', 'post', 'patch', 'delete'], true)) {
            throw new \RuntimeException('Invalid request method.');
        }

        return $this->execute($method, $args[0], $args[1] ?? [], $args[2] ?? [], $args[3] ?? null);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @param array $context
     * @param string|null $type
     *
     * @return array|object|ResponseInterface
     */
    public function execute(string $method, string $uri, array $options = [], array $context = [], string $type = null)
    {

        $response = $this->client->request(strtoupper($method), $uri, $options);

        if (null !== $type) {
            $response = $this->handleResponse($response, $type, $context);
        }

        return $response;
    }

    /**
     * @param ResponseInterface $response
     * @param string $type
     * @param array $context
     *
     * @return array|object
     */
    public function handleResponse(ResponseInterface $response, string $type, array $context = [])
    {
        dump('inside ApiManager');

        $format = $this->getFormat($response->getHeaders()['content-type'][0]);
        $deserializeContext = new DeserializationContext();
        $deserializeContext->setAttribute('context', $context);

        return $this->serializer->deserialize($response->getContent(), $type, $format, $deserializeContext);
    }

    /**
     * @param string $contentType
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    private function getFormat(string $contentType)
    {
        $format = null;

        if (preg_match('#application/json#', $contentType)) {
            $format = 'json';
        } elseif (preg_match('#application/xml#', $contentType)) {
            $format = 'xml';
        }

        if (null === $format) {
            throw new \RuntimeException(sprintf('Unsupported Content-type "%s".', $contentType));
        }

        return $format;
    }
}