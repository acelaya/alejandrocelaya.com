<?php
namespace Acelaya\Website\Router\Http;

use Zend\I18n\Translator\Translator;
use Zend\Mvc\Router\Http\Segment;
use Zend\Mvc\Router\Exception;
use Zend\Stdlib\ArrayUtils;

/**
 * SkippableSegment route.
 * Modified Segment route where you can skip optional segments
 *
 *   'router' => array(
 *       'routes' => array(
 *           'home' => array(
 *               'type' => 'Base\Router\Http\SkippableSegment',
 *               'options' => array(
 *                   'route' => '[/:format][/:lang][/]',
 *                   'constraints' => array(
 *                       'format' => '(?i:html|json|tpl)',
 *                       'lang' => '(?i:nl|en)',
 *                   ),
 *                   'defaults' => array(
 *                       'format' => 'html',
 *                       'lang' => '',
 *                       'controller' => 'Cms\Controller\Home',
 *                       'action' => 'index'
 *                   ),
 *                   'skippable' => array(
 *                       'format' => true,
 *                       'lang' => true,
 *                   )
 *               )
 *           )
 *       )
 */
class SkippableSegment extends Segment
{
    /**
     * @var array map of skippable segments
     */
    protected $skippable = array();

    /**
     * Create a new regex route.
     * @param string $route
     * @param array $constraints
     * @param array $defaults
     * @param array $skippable
     */
    public function __construct(
        $route,
        array $constraints = array(),
        array $defaults = array(),
        array $skippable = array()
    ) {
        $this->defaults = $defaults;
        $this->skippable = $skippable;
        $this->parts    = $this->parseRouteDefinition($route);
        $this->regex    = $this->buildRegex($this->parts, $constraints);
    }

    /**
     * factory(): defined by RouteInterface interface.
     *
     * @see    \Zend\Mvc\Router\RouteInterface::factory()
     * @param  array|\Traversable $options
     * @return Segment
     * @throws Exception\InvalidArgumentException
     */
    public static function factory($options = array())
    {
        if ($options instanceof \Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new Exception\InvalidArgumentException(
                __METHOD__ . ' expects an array or Traversable set of options'
            );
        }

        if (!isset($options['route'])) {
            throw new Exception\InvalidArgumentException('Missing "route" in options array');
        }

        if (!isset($options['constraints'])) {
            $options['constraints'] = array();
        }

        if (!isset($options['defaults'])) {
            $options['defaults'] = array();
        }

        if (!isset($options['skippable'])) {
            $options['skippable'] = array();
        }

        return new static($options['route'], $options['constraints'], $options['defaults'], $options['skippable']);
    }

    /**
     * Build a path.
     *
     * @param  array    $parts
     * @param  array    $mergedParams
     * @param  bool     $isOptional
     * @param  bool     $hasChild
     * @param  array    $options
     * @return string
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    protected function buildPath(array $parts, array $mergedParams, $isOptional, $hasChild, array $options = array())
    {
        if (!empty($this->translationKeys)) {
            if (!isset($options['translator']) || !$options['translator'] instanceof Translator) {
                throw new Exception\RuntimeException('No translator provided');
            }

            $translator = $options['translator'];
            $textDomain = (isset($options['text_domain']) ? $options['text_domain'] : 'default');
            $locale     = (isset($options['locale']) ? $options['locale'] : null);
        }
        $path      = '';
        $skip      = true;
        $skippable = false;

        foreach ($parts as $part) {
            switch ($part[0]) {
                case 'literal':
                    $path .= $part[1];
                    break;

                case 'parameter':
                    $skippable = true;

                    if (
                        !empty($this->skippable[$part[1]])
                        && (
                            !isset($mergedParams[$part[1]])
                            || !isset($this->defaults[$part[1]])
                            || $mergedParams[$part[1]] === $this->defaults[$part[1]]
                        )
                    ) {
                        $this->assembledParams[] = $part[1];
                        break;
                    } elseif (!isset($mergedParams[$part[1]])) {
                        if (!$isOptional || $hasChild) {
                            throw new Exception\InvalidArgumentException(sprintf('Missing parameter "%s"', $part[1]));
                        }

                        return '';
                    } elseif (
                        !$isOptional
                        || $hasChild
                        || !isset($this->defaults[$part[1]])
                        || $this->defaults[$part[1]] !== $mergedParams[$part[1]]
                    ) {
                        $skip = false;
                    }

                    $path .= $this->encode($mergedParams[$part[1]]);

                    $this->assembledParams[] = $part[1];
                    break;

                case 'optional':
                    $skippable    = true;
                    $optionalPart = $this->buildPath($part[1], $mergedParams, true, $hasChild);

                    if ($optionalPart !== '') {
                        $path .= $optionalPart;
                        $skip  = false;
                    }
                    break;

                case 'translated-literal':
                    $path .= $translator->translate($part[1], $textDomain, $locale);
                    break;
            }
        }

        if ($isOptional && $skippable && $skip) {
            return '';
        }

        return $path;
    }
}