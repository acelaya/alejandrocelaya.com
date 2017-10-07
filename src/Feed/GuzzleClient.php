<?php
declare(strict_types=1);

namespace Acelaya\Website\Feed;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Zend\Feed\Reader\Http\ClientInterface as FeedReaderHttpClientInterface;
use Zend\Feed\Reader\Http\Psr7ResponseDecorator;
use Zend\Feed\Reader\Http\ResponseInterface;

class GuzzleClient implements FeedReaderHttpClientInterface
{
    /**
     * @var GuzzleClientInterface
     */
    private $client;

    /**
     * @param GuzzleClientInterface|null $client
     */
    public function __construct(GuzzleClientInterface $client = null)
    {
        $this->client = $client ?: new Client();
    }

    /**
     * Make a GET request to a given URI
     *
     * @param  string $uri
     * @return ResponseInterface
     */
    public function get($uri)
    {
        return new Psr7ResponseDecorator(
            $this->client->request('GET', $uri)
        );
    }
}
