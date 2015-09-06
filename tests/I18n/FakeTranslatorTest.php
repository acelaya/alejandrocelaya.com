<?php
namespace AcelayaTest\Website\I18n;

use Acelaya\Website\I18n\FakeTranslator;
use PHPUnit_Framework_TestCase as TestCase;

class FakeTranslatorTest extends TestCase
{
    public function testTranslate()
    {
        $expected = 'foo';
        $this->assertEquals($expected, FakeTranslator::translate($expected));
    }
}
