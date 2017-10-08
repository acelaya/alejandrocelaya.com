<?php
declare(strict_types=1);

namespace AcelayaTest\Website\Feed\Template\Extension;

use Acelaya\Website\Feed\BlogOptions;
use Acelaya\Website\Feed\Template\Extension\BlogExtension;
use Doctrine\Common\Cache\ArrayCache;
use League\Plates\Engine;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class BlogExtensionTest extends TestCase
{
    /**
     * @var BlogExtension
     */
    private $extension;
    /**
     * @var ArrayCache
     */
    private $cache;
    /**
     * @var BlogOptions
     */
    private $options;

    public function setUp()
    {
        $this->cache = new ArrayCache();
        $this->options = new BlogOptions();

        $this->extension = new BlogExtension($this->cache, $this->options);
    }

    /**
     * @test
     */
    public function functionsAreProperlyregistered()
    {
        $engine = $this->prophesize(Engine::class);

        $engine->registerFunction('render_latest_blog_posts', Argument::type('callable'))->shouldBeCalledTimes(1);

        $this->extension->register($engine->reveal());
    }

    /**
     * @test
     */
    public function emptyListIsReturnedIfPostsAreNotCached()
    {
        $result = $this->extension->renderLatestBlogPosts();

        $this->assertEquals('<ul class="fh5co-links blog-posts"></ul>', $result);
    }

    /**
     * @test
     */
    public function cachedPostsAreProperlyListed()
    {
        $this->cache->save($this->options->getCacheKey(), [
            [
                'link' => 'foo',
                'title' => 'bar',
            ],
            [
                'link' => 'foo2',
                'title' => 'bar2',
            ],
        ]);

        $result = $this->extension->renderLatestBlogPosts();

        $this->assertContains('<li><a target="_blank" href="foo">bar</a></li>', $result);
        $this->assertContains('<li><a target="_blank" href="foo2">bar2</a></li>', $result);
    }
}
