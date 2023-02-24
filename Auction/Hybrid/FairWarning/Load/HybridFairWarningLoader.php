<?php
/**
 * SAM-6315: Unit tests for hybrid fair warning manager
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 17, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Hybrid\FairWarning\Load;

use Sam\Auction\Hybrid\FairWarning\Parse\HybridFairWarningParserCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class HybridFairWarningLoader
 * @package Sam\Auction\Hybrid\FairWarning\Load
 */
class HybridFairWarningLoader extends CustomizableClass
{
    use HybridFairWarningParserCreateTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load warning array for account
     * @param int $accountId
     * @return array
     */
    public function getWarnings(int $accountId): array
    {
        $fairWarningsHybrid = $this->getSettingsManager()->get(Constants\Setting::FAIR_WARNINGS_HYBRID, $accountId);
        $warnings = $this->createHybridFairWarningParser()->parse($fairWarningsHybrid);
        ksort($warnings);
        return $warnings;
    }
}
