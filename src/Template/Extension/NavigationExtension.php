<?php
declare(strict_types=1);

namespace Acelaya\Website\Template\Extension;

use Acelaya\Website\Service\RouteAssemblerInterface;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Zend\I18n\Translator\TranslatorInterface;

use function implode;
use function sprintf;

class NavigationExtension implements ExtensionInterface
{
    /** @var TranslatorInterface */
    protected $translator;
    /** @var RouteAssemblerInterface */
    protected $routeAssembler;
    /** @var array */
    protected $config;

    public function __construct(
        TranslatorInterface $translator,
        RouteAssemblerInterface $routeAssembler,
        array $config = []
    ) {
        $this->translator = $translator;
        $this->routeAssembler = $routeAssembler;
        $this->config = $config;
    }

    public function register(Engine $engine)
    {
        $engine->registerFunction('render_menu', [$this, 'renderMenu']);
        $engine->registerFunction('render_langs_menu', [$this, 'renderLanguagesMenu']);
        $engine->registerFunction('render_social_menu', [$this, 'renderSocialMenu']);
    }

    public function renderMenu($class = 'pull-left left-menu'): string
    {
        $pages = isset($this->config['menu']) ? $this->config['menu'] : [];
        $listElements = [];
        $elementPattern = '<li class="%s"><a href="%s" %s>%s</a></li>';
        $currentRoute = $this->routeAssembler->getCurrentRouteResult();

        foreach ($pages as $page) {
            $active = isset($page['route']) && $currentRoute->getMatchedRouteName() === $page['route'] ? 'active' : '';
            $target = isset($page['target']) ? 'target="_blank"' : '';
            $route = $page['uri'] ?? $this->routeAssembler->assembleUrl($page['route'], true);

            $listElements[] = sprintf(
                $elementPattern,
                $active,
                $route,
                $target,
                $this->translator->translate($page['label'])
            );
        }

        return sprintf('<ul class="%s">%s</ul>', $class, implode('', $listElements));
    }

    public function renderLanguagesMenu(): string
    {
        $pages = $this->config['lang_menu'] ?? [];
        $listElements = [];
        $elementPattern = '<li><a href="%s">%s</a></li>';
        $pageResult = $this->routeAssembler->getCurrentRouteResult();

        foreach ($pages as $page) {
            // Inherit current route if it is not an error page
            $routeName = $pageResult->isSuccess() ? null : 'home';
            $route = $this->routeAssembler->assembleUrl($routeName, $page['params']);

            $listElements[] = sprintf($elementPattern, $route, $page['label']);
        }

        return sprintf('<ul class="pull-right right-menu">%s</ul>', implode('', $listElements));
    }

    public function renderSocialMenu()
    {
        $pages = $this->config['social_menu'] ?? [];
        $listElements = [];
        $elementPattern = '<li><a target="_blank" href="%s"><i class="%s"></i></a></li>';

        foreach ($pages as $page) {
            $listElements[] = sprintf($elementPattern, $page['uri'], $page['icon']);
        }

        return sprintf('<ul class="fh5co-social">%s</ul>', implode('', $listElements));
    }
}
