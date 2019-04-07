<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Feed;

use Acelaya\Website\Feed\GuzzleClient;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\Diactoros\Response;

class GuzzleClientTest extends TestCase
{
    /** @var GuzzleClient */
    protected $client;
    /** @var ObjectProphecy */
    protected $guzzle;

    protected function setUp(): void
    {
        $this->guzzle = $this->prophesize(ClientInterface::class);
        $this->client = new GuzzleClient($this->guzzle->reveal());
    }

    /**
     * @test
     */
    public function responseIsWrapped()
    {
        $this->guzzle->request('GET', 'foo')->willReturn(new Response())
                                                  ->shouldBeCalledTimes(1);
        $this->client->get('foo');
    }
}
