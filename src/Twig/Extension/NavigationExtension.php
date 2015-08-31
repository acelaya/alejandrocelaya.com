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
    
    public function __construct(TranslatorInterface $translator, RouteAssemblerInterface $routeAssembler)
    {
        $this->translator = $translator;
        $this->routeAssembler = $routeAssembler;
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
        $pages = [
            [
                'label' => $this->translator->translate('Skills'),
                'route' => 'skills',
                'icon'  => 'fa-tags',
            ],
            [
                'label' => $this->translator->translate('Projects'),
                'route' => 'projects',
                'icon'  => 'fa-cog',
            ],
            [
                'label' => $this->translator->translate('Contact'),
                'route' => 'contact',
                'icon'  => 'fa fa-envelope',
            ],
            [
                'label'     => $this->translator->translate('Blog'),
                'uri'       => 'http://blog.alejandrocelaya.com',
                'icon'      => 'fa-book',
                'target'    => true,
            ]
        ];

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
        $pages = [
            [
                'label'    => 'Español',
                'class'    => 'es',
                'params'   => [
                    'lang' => 'es'
                ]
            ],
            [
                'label'    => 'English',
                'class'    => 'en',
                'params'   => [
                    'lang' => 'en'
                ]
            ],
        ];

        $listElements = [];
        $elementPattern =
            '<li>' .
                '<a href="%s" class="%s">%s</a>' .
            '</li>';
        foreach ($pages as $page) {
            $route = $this->routeAssembler->assembleUrl(null, $page['params']);

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
