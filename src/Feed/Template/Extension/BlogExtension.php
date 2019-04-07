<?php
declare(strict_types=1);

namespace Acelaya\Website\Feed\Template\Extension;

use Acelaya\Website\Feed\BlogOptions;
use Doctrine\Common\Cache\Cache;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

use function implode;
use function sprintf;

class BlogExtension implements ExtensionInterface
{
    /** @var Cache */
    private $cache;
    /** @var BlogOptions */
    private $blogOptions;

    /**
     * BlogExtension constructor.
     * @param Cache $cache
     * @param BlogOptions $blogOptions
     */
    public function __construct(Cache $cache, BlogOptions $blogOptions)
    {
        $this->cache = $cache;
        $this->blogOptions = $blogOptions;
    }

    public function register(Engine $engine)
    {
        $engine->registerFunction('render_latest_blog_posts', [$this, 'renderLatestBlogPosts']);
    }

    public function renderLatestBlogPosts()
    {
        $cacheId = $this->blogOptions->getCacheKey();
        $posts = $this->cache->contains($cacheId) ? $this->cache->fetch($cacheId) : [];
        $elements = [];

        foreach ($posts as $post) {
            $elements[] = sprintf('<li><a target="_blank" href="%s">%s</a></li>', $post['link'], $post['title']);
        }

        return sprintf('<ul class="fh5co-links blog-posts">%s</ul>', implode('', $elements));
    }
}
