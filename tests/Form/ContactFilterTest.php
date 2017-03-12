<?php
namespace AcelayaTest\Website\Form;

use Acelaya\Website\Form\ContactFilter;
use PHPUnit\Framework\TestCase;
use ReCaptcha\ReCaptcha;
use ReCaptcha\Response;

class ContactFilterTest extends TestCase
{
    /**
     * @var ContactFilter
     */
    protected $filter;

    public function setUp()
    {
        $recaptcha = $this->prophesize(ReCaptcha::class);
        $recaptcha->verify('foo')->willReturn(new Response(true));

        $this->filter = new ContactFilter($recaptcha->reveal());
    }

    public function testWithEmptyValues()
    {
        $this->filter->setData([]);
        $this->assertFalse($this->filter->isValid());
    }

    public function testWithRequiredValues()
    {
        $this->filter->setData([
            ContactFilter::NAME => 'name',
            ContactFilter::EMAIL => 'email',
            ContactFilter::SUBJECT => 'subject',
            ContactFilter::COMMENTS => 'comments',
            ContactFilter::RECAPTCHA => 'foo',
        ]);
        $this->assertTrue($this->filter->isValid());
    }

    public function testFilters()
    {
        $this->filter->setData([
            ContactFilter::NAME => '  name',
            ContactFilter::EMAIL => 'email  <br>',
            ContactFilter::SUBJECT => 'subject <script></script>  ',
            ContactFilter::COMMENTS => '  <p>Hi!</p> comments',
            ContactFilter::RECAPTCHA => 'foo',
        ]);
        $this->assertTrue($this->filter->isValid());
        $this->assertEquals([
            ContactFilter::NAME => 'name',
            ContactFilter::EMAIL => 'email',
            ContactFilter::SUBJECT => 'subject',
            ContactFilter::COMMENTS => 'Hi! comments',
            ContactFilter::RECAPTCHA => 'foo',
        ], $this->filter->getValues());
    }
}
