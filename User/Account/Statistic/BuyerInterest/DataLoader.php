<?php
/**
 * SAM-4942: Refactor buyer_interests.php cron script logic
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-04-05
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Account\Statistic\BuyerInterest;

use DateTime;
use LotItem;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;
use User;

/**
 * Class DataLoader
 */
class DataLoader extends CustomizableClass
{
    use LotItemReadRepositoryCreateTrait;
    use UserReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $winningBidderId
     * @param DateTime $soldDateFrom
     * @param DateTime $soldDateTo
     * @return LotItem[]
     */
    public function loadLotItemsByWinningBidderIdAndDateSold(int $winningBidderId, DateTime $soldDateFrom, DateTime $soldDateTo): array
    {
        return $this->createLotItemReadRepository()
            ->filterActive(true)
            ->filterDateSoldGreater($soldDateFrom->format(Constants\Date::ISO))
            ->filterDateSoldLess($soldDateTo->format(Constants\Date::ISO))
            ->filterWinningBidderId($winningBidderId)
            ->joinAccountFilterActive(true)
            ->loadEntities();
    }

    /**
     * @param DateTime $soldDateFrom
     * @param DateTime $soldDateTo
     * @return array|User[]
     */
    public function loadUsersByDateSold(DateTime $soldDateFrom, DateTime $soldDateTo): array
    {
        return $this->createUserReadRepository()
            ->filterLogDateGreater($soldDateFrom->format(Constants\Date::ISO))
            ->filterLogDateLess($soldDateTo->format(Constants\Date::ISO))
            ->filterSubqueryHasBidderGreater(0)
            ->filterUserStatusId(Constants\User::AVAILABLE_USER_STATUSES)
            ->joinAccountFilterActive(true)
            ->loadEntities();
    }
}
