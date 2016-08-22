<?php
namespace Acelaya\Website\Feed;

use Acelaya\ZsmAnnotatedServices\Annotation\Inject;
use Zend\Stdlib\AbstractOptions;

class BlogOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $url = '';
    /**
     * @var string
     */
    protected $feed = '';
    /**
     * @var int
     */
    protected $elementsToDisplay = 5;

    /**
     * BlogFeedOptions constructor.
     * @param null $options
     *
     * @Inject({"config.blog"})
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getFeed(): string
    {
        return $this->feed;
    }

    /**
     * @param string $feed
     * @return $this
     */
    public function setFeed(string $feed)
    {
        $this->feed = $feed;
        return $this;
    }

    /**
     * @return int
     */
    public function getElementsToDisplay(): int
    {
        return $this->elementsToDisplay;
    }

    /**
     * @param int $elementsToDisplay
     * @return $this
     */
    public function setElementsToDisplay(int $elementsToDisplay)
    {
        $this->elementsToDisplay = $elementsToDisplay;
        return $this;
    }
}
