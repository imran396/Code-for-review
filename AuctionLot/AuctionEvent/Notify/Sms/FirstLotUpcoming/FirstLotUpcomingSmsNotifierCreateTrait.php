<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\AuctionEvent\Notify\Sms\FirstLotUpcoming;

/**
 * Trait FirstLotUpcomingSmsNotifierCreateTrait
 * @package Sam\AuctionLot\AuctionEvent\Notify\Sms\FirstLotUpcoming
 */
trait FirstLotUpcomingSmsNotifierCreateTrait
{
    /**
     * @var FirstLotUpcomingSmsNotifier|null
     */
    protected ?FirstLotUpcomingSmsNotifier $firstLotUpcomingSmsNotifier = null;

    /**
     * @return FirstLotUpcomingSmsNotifier
     */
    protected function createFirstLotUpcomingSmsNotifier(): FirstLotUpcomingSmsNotifier
    {
        return $this->firstLotUpcomingSmsNotifier ?: FirstLotUpcomingSmsNotifier::new();
    }

    /**
     * @param FirstLotUpcomingSmsNotifier $firstLotUpcomingSmsNotifier
     * @return static
     * @internal
     */
    public function setFirstLotUpcomingSmsNotifier(FirstLotUpcomingSmsNotifier $firstLotUpcomingSmsNotifier): static
    {
        $this->firstLotUpcomingSmsNotifier = $firstLotUpcomingSmsNotifier;
        return $this;
    }
}
