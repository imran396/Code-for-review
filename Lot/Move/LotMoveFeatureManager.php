<?php
/**
 * SAM-4005: Lot moving logic
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/2/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Move;

use Auction;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class LotMoveFeatureManager
 * @package Sam\Lot\Move
 */
class LotMoveFeatureManager extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
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
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->cfg()->get('core->admin->auction->lots->move->enabled');
    }

    /**
     * @param int $accountId
     * @return bool
     */
    public function isAvailableForAccountId(int $accountId): bool
    {
        $isAvailable = $this->isEnabled()
            && !$this->getSettingsManager()->get(Constants\Setting::HIDE_MOVETOSALE, $accountId);
        return $isAvailable;
    }

    /**
     * Check if "Move" action available for auction
     * @param Auction $auction
     * @return bool
     */
    public function isAvailable(Auction $auction): bool
    {
        return $this->isAvailableForAccountId($auction->AccountId);
    }
}
