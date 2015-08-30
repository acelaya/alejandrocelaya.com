<?php
namespace Acelaya\Website\Twig\Extension;

use Zend\Expressive\Router\RouterInterface;
use Zend\I18n\Translator\TranslatorInterface;

class NavigationExtension extends AbstractExtension
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;
    /**
     * @var RouterInterface
     */
    protected $router;
    
    public function __construct(TranslatorInterface $translator, RouterInterface $router)
    {
        $this->translator = $translator;
        $this->router = $router;
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
//                'route' => 'skills',
                'route' => 'home',
                'icon'  => 'fa-tags',
            ],
            [
                'label' => $this->translator->translate('Projects'),
//                'route' => 'projects',
                'route' => 'home',
                'icon'  => 'fa-cog',
            ],
            [
                'label' => $this->translator->translate('Contact'),
//                'route' => 'contact',
                'route' => 'home',
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
        foreach ($pages as $page) {
            $active = ''; // TODO Check current route in order to set element as active
            $target = isset($page['target']) ? 'target="_blank"' : '';
            $route = isset($page['uri']) ? $page['uri'] : $this->router->generateUri($page['route']);

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
                'route'    => 'lang',
                'params'   => [
                    'lang' => 'es'
                ]
            ],
            [
                'label'    => 'English',
                'class'    => 'en',
                'route'    => 'lang',
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
            $route = $this->router->generateUri($page['route'], $page['params']);

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
