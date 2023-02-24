<?php
/**
 * SAM-5638: Refactor SMS Text Message notification sender for the auction event upcoming lot items
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\AuctionEvent\Notify\Sms\Cron\Internal\Load;

use AuctionLotItem;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Lot\Search\Query\Build\Helper\LotSearchQueryBuilderHelperCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Unnotified timed lots loader
 *
 * Class DataLoader
 * @package Sam\AuctionLot\AuctionEvent\Notify\Sms\Cron\Internal\Load
 * @internal
 */
class DataLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use CurrentDateTrait;
    use LotSearchQueryBuilderHelperCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return all unclosed timed online lots
     * @return AuctionLotItem[]
     */
    public function loadSmsUnnotifiedTimedLots(): array
    {
        return $this->prepareRepository()->loadEntities();
    }

    /**
     * @return AuctionLotItemReadRepository
     */
    private function prepareRepository(): AuctionLotItemReadRepository
    {
        $repository = $this->createAuctionLotItemReadRepository();

        $repository
            ->filterLotStatusId(Constants\Lot::LS_ACTIVE)
            ->joinAccountFilterActive(true)
            ->joinAuctionDynamic()
            ->joinAuctionFilterAuctionType(Constants\Auction::TIMED)
            ->joinAuctionLotItemCache();

        $repository->inlineCondition("a.event_id <> '' AND a.notify_x_minutes > 0 ");
        $repository->inlineCondition('ali.text_msg_notified = false');

        $lotEndDateExpr = $this->createLotSearchQueryBuilderHelper()->getTimedLotEndDateExpr();
        $currentTimestamp = $this->getCurrentDateUtc()->getTimestamp();
        $repository->inlineCondition(sprintf('(UNIX_TIMESTAMP(%s) - %d) > 0', $lotEndDateExpr, $currentTimestamp));
        $repository->inlineCondition(sprintf('(UNIX_TIMESTAMP(%s) - %d) <= (a.notify_x_minutes * 60)', $lotEndDateExpr, $currentTimestamp));

        return $repository;
    }
}
