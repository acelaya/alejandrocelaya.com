<?php
namespace Acelaya\Website\Form;

use ReCaptcha\ReCaptcha;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class RecaptchaValidator extends AbstractValidator
{
    /**
     * @var Recaptcha
     */
    protected $recaptcha;

    public function __construct(Recaptcha $recaptcha)
    {
        parent::__construct();
        $this->recaptcha = $recaptcha;
    }

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        $resp = $this->recaptcha->verify($value, $_SERVER['REMOTE_ADDR']); // TODO Don't use global $_SERVER
        return $resp->isSuccess();
    }
}
