<?php
namespace Acelaya\Website\Template\Extension;

abstract class AbstractExtension extends \Twig_Extension
{
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName(): string
    {
        return static::class;
    }
}
