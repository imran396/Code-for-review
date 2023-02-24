<?php
/**
 * Tracking of categories of purchases by users is to be used for marketing purposes
 *
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
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserAccountStats\UserAccountStatsWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class BuyerInterestUpdater
 */
class BuyerInterestUpdater extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use LotCategoryLoaderAwareTrait;
    use UserAccountStatsWriteRepositoryAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * process to update user_account_stats.purchased_category
     * @param int $editorUserId
     */
    public function process(int $editorUserId): void
    {
        $currentDateUtc = $this->getCurrentDateUtc();
        $endDateTime = $this->getCurrentDateUtc();
        $startDateTime = date_modify($currentDateUtc, '-2 day');
        $users = DataLoader::new()->loadUsersByDateSold($startDateTime, $endDateTime);
        $datePeriod = $this->getCurrentDateUtc();
        /** @var DateTime $datePeriod */
        $datePeriod = date_modify($datePeriod, '-' . $this->cfg()->get('core->user->buyerInterestPeriod') . ' month');
        foreach ($users as $user) {
            $allLotCategoryIds = [];
            $lotCategoryIdList = '';
            $lotItems = DataLoader::new()->loadLotItemsByWinningBidderIdAndDateSold($user->Id, $datePeriod, $endDateTime);
            foreach ($lotItems as $lotItem) {
                $lotCategoryIds = $this->getLotCategoryLoader()->loadIdsForLot($lotItem->Id);
                $allLotCategoryIds = array_merge($allLotCategoryIds, $lotCategoryIds);
            }
            $allLotCategoryIds = array_unique($allLotCategoryIds);
            asort($allLotCategoryIds);
            foreach ($allLotCategoryIds as $lotCategoryId) {
                $lotCategoryIdList .= $lotCategoryId . ",";
            }
            $lotCategoryIdList = trim($lotCategoryIdList, ",");
            $userAccountStats = $this->getUserLoader()->loadUserAccountStatsOrCreate($user->Id, $user->AccountId);
            $userAccountStats->PurchasedCategory = $lotCategoryIdList;
            $this->getUserAccountStatsWriteRepository()->saveWithModifier($userAccountStats, $editorUserId);
        }
    }
}
