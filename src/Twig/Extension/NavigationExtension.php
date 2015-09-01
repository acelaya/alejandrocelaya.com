<?php
namespace Acelaya\Website\Twig\Extension;

use Acelaya\Website\Service\RouteAssemblerInterface;
use Zend\I18n\Translator\TranslatorInterface;

class NavigationExtension extends AbstractExtension
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;
    /**
     * @var RouteAssemblerInterface
     */
    protected $routeAssembler;
    /**
     * @var array
     */
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
    
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('render_menu', [$this, 'renderMenu'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('render_langs_menu', [$this, 'renderLanguagesMenu'], ['is_safe' => ['html']]),
        ];
    }

    public function renderMenu()
    {
        $pages = isset($this->config['menu']) ? $this->config['menu'] : [];
        $listElements = [];
        $elementPattern =
            '<li class="%s">' .
                '<a href="%s" %s><i class="fa %s"></i> %s</a>' .
            '</li>';
        $currentRoute = $this->routeAssembler->getCurrentRouteResult();
        foreach ($pages as $page) {
            $active = isset($page['route']) && $currentRoute->getMatchedRouteName() === $page['route'] ? 'active' : '';
            $target = isset($page['target']) ? 'target="_blank"' : '';
            $route = isset($page['uri']) ? $page['uri'] : $this->routeAssembler->assembleUrl($page['route'], true);

            $listElements[] = sprintf(
                $elementPattern,
                $active,
                $route,
                $target,
                $page['icon'],
                $page['label']
            );
        }

        return sprintf('<ul class="nav navbar-nav main-menu">%s</ul>', implode('', $listElements));
    }

    public function renderLanguagesMenu()
    {
        $pages = isset($this->config['lang_menu']) ? $this->config['lang_menu'] : [];
        $listElements = [];
        $elementPattern =
            '<li>' .
                '<a href="%s" class="%s">%s</a>' .
            '</li>';
        $pageResult = $this->routeAssembler->getCurrentRouteResult();

        foreach ($pages as $page) {
            // Inherit current route if it is not an error page
            $routeName = $pageResult->isSuccess() ? null : 'home';
            $route = $this->routeAssembler->assembleUrl($routeName, $page['params']);

            $listElements[] = sprintf(
                $elementPattern,
                $route,
                $page['class'],
                $page['label']
            );
        }

        return sprintf('<ul class="dropdown-menu langs-menu">%s</ul>', implode('', $listElements));
    }
}
