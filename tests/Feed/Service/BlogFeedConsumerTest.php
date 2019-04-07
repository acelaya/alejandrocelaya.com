<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Feed\Service;

use Acelaya\Website\Feed\BlogOptions;
use Acelaya\Website\Feed\Service\BlogFeedConsumer;
use Doctrine\Common\Cache;
use Doctrine\Common\Cache\ArrayCache;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\Feed\Reader\Http\ClientInterface;
use Zend\Feed\Reader\Http\Response;

class BlogFeedConsumerTest extends TestCase
{
    /** @var BlogFeedConsumer */
    protected $service;
    /** @var Cache\Cache */
    protected $feedCache;
    /** @var Cache\ClearableCache */
    protected $viewsCache;
    /** @var ObjectProphecy */
    protected $httpClient;
    /** @var BlogOptions */
    protected $options;

    protected function setUp(): void
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
<generator uri="http://spress.yosymfony.com/">Spress</generator>
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

        $this->feedCache = new ArrayCache();
        $this->viewsCache = new ArrayCache();
        $this->options = new BlogOptions([
            'url' => 'foo',
            'feed' => 'bar',
            'elements_to_display' => 2,
        ]);

        $this->service = new BlogFeedConsumer(
            $this->httpClient->reveal(),
            $this->feedCache,
            $this->viewsCache,
            $this->options
        );
    }

    /**
     * @test
     */
    public function nonCachedResultOnlySavesFeed()
    {
        $this->assertFalse($this->feedCache->contains($this->options->getCacheKey()));
        $this->service->refreshFeed();
        $this->assertTrue($this->feedCache->contains($this->options->getCacheKey()));
    }

    /**
     * @test
     */
    public function cachedContentIsReturnedWhenEqualToProcessed()
    {
        $this->feedCache->save($this->options->getCacheKey(), [[
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
        $this->viewsCache->save('foo', 'bar');
        $this->feedCache->save($this->options->getCacheKey(), [[
            'link' => 'something else',
        ]]);
        $this->service->refreshFeed();
        $this->assertFalse($this->viewsCache->contains('foo'));
    }
}
