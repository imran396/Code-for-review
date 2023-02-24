<?php
/**
 * SAM-3528 : Captcha alternative on other pages
 * https://bidpath.atlassian.net/browse/SAM-3528
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/29/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Security\Captcha\Alternative\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;

/**
 * Class AlternativeCaptchaValidator
 * @package Sam\Security\Captcha\Alternative\Validate
 */
class AlternativeCaptchaValidator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use TranslatorAwareTrait;

    public const ERR_SECURITY_NOT_MEET = 1;

    protected ?int $startTimestamp = null;
    protected ?string $honeyPot = null;
    protected bool $isAltCheckBox = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        // Init ResultStatusCollector
        $tr = $this->getTranslator();
        $errorMessages = [
            self::ERR_SECURITY_NOT_MEET => static fn() => $tr->translate('GENERAL_ERR_CAPTCHA_ALT_CONTACT', 'general'),
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getStartTimestamp(): ?int
    {
        return $this->startTimestamp;
    }

    /**
     * @param int $startTimestamp
     * @return static
     */
    public function setStartTimestamp(int $startTimestamp): static
    {
        $this->startTimestamp = $startTimestamp;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHoneyPot(): ?string
    {
        return $this->honeyPot;
    }

    /**
     * @param string $honeyPot
     * @return static
     */
    public function setHoneyPot(string $honeyPot): static
    {
        $this->honeyPot = trim($honeyPot);
        return $this;
    }

    /**
     * @return bool
     */
    public function isAltCheckBox(): bool
    {
        return $this->isAltCheckBox;
    }

    /**
     * @param bool $isAltCheckBox
     * @return static
     */
    public function enableAltCheckBox(bool $isAltCheckBox): static
    {
        $this->isAltCheckBox = $isAltCheckBox;
        return $this;
    }

    /**
     * Returns error message from error codes.
     * @return string
     */
    public function getErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(
            [self::ERR_SECURITY_NOT_MEET]
        );
    }

    /**
     * Check minimum security measures in order to reject form submit.
     * @return bool
     */
    public function validate(): bool
    {
        $counter = 0;
        $timeNow = time();

        if ($this->isAltCheckBox) {
            $counter++;
        }
        if (($timeNow - $this->startTimestamp) > $this->cfg()->get('core->captcha->alternative->time')) {
            $counter++;
        }
        if (!$this->honeyPot) {
            $counter++;
        }
        $meet = $counter > $this->cfg()->get('core->captcha->alternative->minReq');
        if (!$meet) {
            $this->getResultStatusCollector()->addError(self::ERR_SECURITY_NOT_MEET);
        }
        return $meet;
    }
}
