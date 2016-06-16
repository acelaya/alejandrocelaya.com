<?php
namespace Acelaya\Website\Form;

use ReCaptcha\ReCaptcha;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class RecaptchaValidator extends AbstractValidator
{
    /**
     * @var ReCaptcha
     */
    protected $recaptcha;

    public function __construct(ReCaptcha $recaptcha)
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
    public function isValid($value): bool
    {
        $resp = $this->recaptcha->verify($value);
        return $resp->isSuccess();
    }
}
