<?php
namespace AcelayaTest\Website\Form;

use Acelaya\Website\Form\ContactFilter;
use PHPUnit_Framework_TestCase as TestCase;

class ContactFilterTest extends TestCase
{
    /**
     * @var ContactFilter
     */
    protected $filter;

    public function setUp()
    {
        $this->filter = new ContactFilter();
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
        ]);
        $this->assertTrue($this->filter->isValid());
        $this->assertEquals([
            ContactFilter::NAME => 'name',
            ContactFilter::EMAIL => 'email',
            ContactFilter::SUBJECT => 'subject',
            ContactFilter::COMMENTS => 'Hi! comments',
        ], $this->filter->getValues());
    }
}
