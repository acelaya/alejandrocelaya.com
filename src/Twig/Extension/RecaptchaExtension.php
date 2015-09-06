<?php
namespace Acelaya\Website\Twig\Extension;

class RecaptchaExtension extends AbstractExtension
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('recaptcha_public', [$this, 'getRecapcthaPublicKey']),
            new \Twig_SimpleFunction('recaptcha_input', [$this, 'renderInput'], ['is_safe' => ['html']]),
        ];
    }

    public function getRecapcthaPublicKey()
    {
        return $this->config['public_key'];
    }

    public function renderInput($lang = 'en')
    {
        return '
            <script
            src="https://www.google.com/recaptcha/api.js?hl=' . $lang . '">
            </script>
            <div class="g-recaptcha"
                 data-sitekey="' . $this->getRecapcthaPublicKey() . '"
                 data-callback="recaptchaCallaback">
            </div>
        ';
    }
}
