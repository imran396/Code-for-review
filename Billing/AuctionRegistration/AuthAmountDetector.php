<?php
/**
 * SAM-4932: Authorization/Capture must skip when Authorization Amount is selected as '0' at Auction Level
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           05/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\AuctionRegistration;

use Auction;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class AuthAmountDetector
 * @package Sam\Billing\AuctionRegistration
 */
class AuthAmountDetector extends CustomizableClass
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
     * @param Auction $auction
     * @return float
     */
    public function detect(Auction $auction): float
    {
        if ($auction->AuthorizationAmount !== null) {
            if (Floating::eq($auction->AuthorizationAmount, 0.)) {
                return 0.;
            }
            $authAmount = $auction->AuthorizationAmount;
        } else {
            $authAmount = $this->getSettingsManager()->get(Constants\Setting::ON_AUCTION_REGISTRATION_AMOUNT, $auction->AccountId);
            if (!$authAmount) {
                $authAmount = $this->cfg()->get('core->billing->userAuthorization->amount');
            }
        }
        $authAmount = Cast::toFloat($authAmount);
        return $authAmount;
    }
}
