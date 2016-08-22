<?php
namespace AcelayaTest\Website\Feed\Service;

use Acelaya\Website\Feed\BlogOptions;
use Acelaya\Website\Feed\Service\BlogFeedConsumer;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use PHPUnit_Framework_TestCase as TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\Feed\Reader\Http\ClientInterface;
use Zend\Feed\Reader\Http\Response;

class BlogFeedConsumerTest extends TestCase
{
    /**
     * @var BlogFeedConsumer
     */
    protected $service;
    /**
     * @var Cache
     */
    protected $cache;
    /**
     * @var ObjectProphecy
     */
    protected $httpClient;
    /**
     * @var BlogOptions
     */
    protected $options;

    public function setUp()
    {
        $this->httpClient = $this->prophesize(ClientInterface::class);
        $this->httpClient->get(Argument::any())->willReturn(new Response(200, <<<EOF
<feed xmlns="http://www.w3.org/2005/Atom">
<title>
<![CDATA[ Alejandro Celaya | Blog ]]>
</title>
<link href="https://blog.alejandrocelaya.com/atom.xml" rel="self"/>
<link href="https://blog.alejandrocelaya.com/"/>
<updated>2016-08-16T20:17:20+02:00</updated>
<id>https://blog.alejandrocelaya.com/</id>
<generator uri="http://sculpin.io/">Sculpin</generator>
<entry>
<title type="html">
<![CDATA[
Entry one
]]>
</title>
<link href="https://blog.alejandrocelaya.com/entry-one/"/>
<updated>2016-08-16T00:00:00+02:00</updated>
<id>
https://blog.alejandrocelaya.com/entry-one/
</id>
<content type="html">
<![CDATA[
Something
]]>
</content>
</entry>
<entry>
<title type="html">
<![CDATA[
Entry two
]]>
</title>
<link href="https://blog.alejandrocelaya.com/entry-two/"/>
<updated>2016-08-16T00:00:00+02:00</updated>
<id>
https://blog.alejandrocelaya.com/entry-two/
</id>
<content type="html">
<![CDATA[
Something
]]>
</content>
</entry>
<entry>
<title type="html">
<![CDATA[
Entry three
]]>
</title>
<link href="https://blog.alejandrocelaya.com/entry-three/"/>
<updated>2016-08-16T00:00:00+02:00</updated>
<id>
https://blog.alejandrocelaya.com/entry-three/
</id>
<content type="html">
<![CDATA[
Something
]]>
</content>
</entry>
</feed>
EOF
        ))->shouldBeCalledTimes(1);

        $this->cache = new ArrayCache();
        $this->options = new BlogOptions([
            'url' => 'foo',
            'feed' => 'bar',
            'elements_to_display' => 2,
        ]);

        $this->service = new BlogFeedConsumer($this->httpClient->reveal(), $this->cache, $this->options);
    }

    /**
     * @test
     */
    public function nonCachedResultOnlySavesFeed()
    {
        $this->assertFalse($this->cache->contains($this->options->getCacheKey()));
        $this->service->refreshFeed();
        $this->assertTrue($this->cache->contains($this->options->getCacheKey()));
    }

    /**
     * @test
     */
    public function cachedContentIsReturnedWhenEqualToProcessed()
    {
        $this->cache->save($this->options->getCacheKey(), [[
            'link' => 'https://blog.alejandrocelaya.com/entry-one/',
        ]]);
        $feed = $this->service->refreshFeed();
        $this->assertCount(2, $feed);
    }

    /**
     * @test
     */
    public function cacheIsDeletedWhenFeedHasChyanged()
    {
        $this->cache->save('foo', 'bar');
        $this->cache->save($this->options->getCacheKey(), [[
            'link' => 'something else',
        ]]);
        $this->service->refreshFeed();
        $this->assertFalse($this->cache->contains('foo'));
    }
}
