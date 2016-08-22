<?php
namespace Acelaya\Website\Feed\Twig\Extension;

use Acelaya\Website\Feed\BlogOptions;
use Acelaya\Website\Twig\Extension\AbstractExtension;
use Doctrine\Common\Cache\Cache;

class BlogExtension extends AbstractExtension
{
    /**
     * @var Cache
     */
    private $cache;
    /**
     * @var BlogOptions
     */
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

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'render_latest_blog_posts',
                [$this, 'renderLatestBlogPosts'],
                ['is_safe' => ['html']]
            ),
        ];
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
