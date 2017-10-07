<?php
declare(strict_types=1);
namespace Acelaya\Website\Form;

use Acelaya\ZsmAnnotatedServices\Annotation\Inject;
use ReCaptcha\ReCaptcha;
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
    const RECAPTCHA = 'g-recaptcha-response';

    /**
     * @var Recaptcha
     */
    protected $recaptcha;

    /**
     * ContactFilter constructor.
     * @param ReCaptcha $recaptcha
     *
     * @Inject({ReCaptcha::class})
     */
    public function __construct(ReCaptcha $recaptcha)
    {
        $this->recaptcha = $recaptcha;
        $this->init();
    }

    public function init()
    {
        $this->add($this->createInput(self::NAME));
        $this->add($this->createInput(self::EMAIL));
        $this->add($this->createInput(self::SUBJECT));
        $this->add($this->createInput(self::COMMENTS));

        $recaptchaInput = $this->createInput(self::RECAPTCHA);
        $recaptchaInput->getValidatorChain()->attach(new RecaptchaValidator($this->recaptcha));
        $this->add($recaptchaInput);
    }

    /**
     * @param $name
     * @param bool|true $required
     * @return Input
     */
    protected function createInput($name, $required = true): Input
    {
        $input = new Input($name);
        $input->setRequired($required)
              ->getFilterChain()->attach(new StripTags())
                                ->attach(new StringTrim());
        return $input;
    }
}
