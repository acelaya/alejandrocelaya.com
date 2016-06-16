<?php
namespace Acelaya\Website\I18n;

class FakeTranslator
{
    public static function translate(string $message): string
    {
        return $message;
    }
}
