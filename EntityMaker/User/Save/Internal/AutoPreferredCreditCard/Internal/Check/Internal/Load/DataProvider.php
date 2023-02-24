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

namespace Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard\Internal\Check\Internal\Load;

use Sam\Billing\CreditCard\Build\CcNumberEncrypter;
use Sam\Billing\CreditCard\Load\CreditCardLoader;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManager;

/**
 * Class DataProvider
 * @package Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard\Internal\Check\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadCreditCardIdByName(string $ccType, bool $isReadOnlyDb = false): ?int
    {
        return CreditCardLoader::new()->loadByName($ccType, $isReadOnlyDb)->Id ?? null;
    }

    public function createHash(string $ccNumber): string
    {
        return CcNumberEncrypter::new()->createHash($ccNumber);
    }

    public function loadAutoPreferredCreditCard(): bool
    {
        return (bool)SettingsManager::new()->getForMain(Constants\Setting::AUTO_PREFERRED_CREDIT_CARD);
    }
}
