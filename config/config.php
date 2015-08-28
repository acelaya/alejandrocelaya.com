<?php
use Zend\Config\Factory;

return Factory::fromFiles(
    glob(__DIR__ . '/autoload/{,*.}{global,local}.php', GLOB_BRACE)
);
