<?php
declare(strict_types=1);

namespace Acelaya\Website\Template\Extension;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class RecaptchaExtension implements ExtensionInterface
{
    /** @var array */
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function register(Engine $engine)
    {
        $engine->registerFunction('recaptcha_public', [$this, 'getRecapcthaPublicKey']);
        $engine->registerFunction('recaptcha_input', [$this, 'renderInput']);
    }

    public function getRecapcthaPublicKey(): string
    {
        return $this->config['public_key'];
    }

    public function renderInput(string $lang = 'en'): string
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
