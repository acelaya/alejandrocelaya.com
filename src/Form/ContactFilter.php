<?php
namespace Acelaya\Website\Form;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;

class ContactFilter extends InputFilter
{
    const NAME = 'name';
    const EMAIL = 'email';
    const SUBJECT = 'subject';
    const COMMENTS = 'comments';

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->add($this->createInput(self::NAME));
        $this->add($this->createInput(self::EMAIL));
        $this->add($this->createInput(self::SUBJECT));
        $this->add($this->createInput(self::COMMENTS));
    }

    /**
     * @param $name
     * @param bool|true $required
     * @return Input
     */
    protected function createInput($name, $required = true)
    {
        $input = new Input($name);
        $input->setRequired($required)
              ->getFilterChain()->attach(new StripTags())
                                ->attach(new StringTrim());
        return $input;
    }
}
