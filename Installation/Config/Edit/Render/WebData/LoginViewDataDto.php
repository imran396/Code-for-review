<?php
/**
 * Data Transfer Object (DTO) with data for using in Installation settings login form View.
 *
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-16, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Installation\Config\Edit\Render\WebData;


use Sam\Core\Service\CustomizableClass;

/**
 * Class ViewDataValueObjectForLoginForm
 * @package Sam\Installation\Config
 */
class LoginViewDataDto extends CustomizableClass
{
    /**
     * @var bool
     */
    protected ?bool $showLoginForm = null;

    /**
     * Web form submit action url.
     * @var string|null
     */
    protected ?string $formActionUrl = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $formActionUrl
     * @param bool $showLoginForm
     * @return $this
     */
    public function construct(string $formActionUrl, bool $showLoginForm): static
    {
        $this
            ->setFormActionUrl($formActionUrl)
            ->enableShowLoginForm($showLoginForm);

        return $this;
    }

    /**
     * @return string
     */
    public function getFormActionUrl(): string
    {
        return $this->formActionUrl;
    }

    /**
     * @param string $formActionUrl
     * @return $this
     */
    public function setFormActionUrl(string $formActionUrl): static
    {
        $this->formActionUrl = $formActionUrl;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShowLoginForm(): bool
    {
        return $this->showLoginForm;
    }

    /**
     * @param bool $showLoginForm
     * @return $this
     */
    public function enableShowLoginForm(bool $showLoginForm): static
    {
        $this->showLoginForm = $showLoginForm;
        return $this;
    }
}
