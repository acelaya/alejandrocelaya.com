<?php
declare(strict_types=1);
namespace AcelayaTest\Website\I18n;

use Acelaya\Website\I18n\FakeTranslator;
use PHPUnit\Framework\TestCase;

class FakeTranslatorTest extends TestCase
{
    public function testTranslate()
    {
        $expected = 'foo';
        $this->assertEquals($expected, FakeTranslator::translate($expected));
    }
}
