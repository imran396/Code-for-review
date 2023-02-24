<?php
/**
 * SAM-6853: Settings > System Parameters > User options - "Auto assign Preferred bidder privileges upon credit card update" condition is not working properly
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard\Internal\Check;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class EffectCheckingResult
 * @package Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard\Internal\Check
 */
class AutoPreferredCreditCardEffectCheckingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const INFO_MODE_IS_NOT_WEB_RESPONSIVE = 1;
    public const INFO_AUTO_PREFERRED_CREDIT_CARD_DISABLED = 2;
    public const INFO_TARGET_USER_WITHOUT_BIDDER_ROLE = 3;
    public const INFO_CC_INFO_NOT_MODIFIED = 4;

    public const OK_CC_NUMBER_MODIFIED = 11;
    public const OK_CC_EXP_DATE_MODIFIED = 12;
    public const OK_CC_TYPE_MODIFIED = 13;

    /** @var string[] */
    protected const INFO_MESSAGES = [
        self::INFO_MODE_IS_NOT_WEB_RESPONSIVE => 'Input mode is not web responsive',
        self::INFO_AUTO_PREFERRED_CREDIT_CARD_DISABLED => '"Auto Preferred Credit Card" system parameter disabled',
        self::INFO_TARGET_USER_WITHOUT_BIDDER_ROLE => 'Target user does not have bidder role',
        self::INFO_CC_INFO_NOT_MODIFIED => 'CC Info is not modified',
    ];

    /** @var string[] */
    protected const SUCCESS_MESSAGES = [
        self::OK_CC_NUMBER_MODIFIED => 'CC Number modified',
        self::OK_CC_EXP_DATE_MODIFIED => 'CC Expiration Date modified',
        self::OK_CC_TYPE_MODIFIED => 'CC type modified',
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        $this->getResultStatusCollector()->construct([], self::SUCCESS_MESSAGES, [], self::INFO_MESSAGES);
        return $this;
    }

    public function addInfo(int $code): static
    {
        $this->getResultStatusCollector()->addInfo($code);
        return $this;
    }

    public function addSuccess(int $code): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
        return $this;
    }

    public function isEffect(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function logMessage(): string
    {
        $collector = $this->getResultStatusCollector();
        if ($collector->hasSuccess()) {
            return 'Preferred Bidder privilege must be set, because ' . $collector->getConcatenatedSuccessMessage(', ');
        }
        return 'Preferred Bidder privilege not affected, because ' . $collector->getConcatenatedInfoMessage(', ');
    }
}
