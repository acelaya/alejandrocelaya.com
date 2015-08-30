<?php
namespace Acelaya\Website\Twig\Extension;

abstract class AbstractExtension extends \Twig_Extension
{
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return static::class;
    }
}
