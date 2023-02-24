<?php
/**
 * SAM-5651: Refactor Lot No auto filling service
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\LotNo\Fill\CustomField\Validate;

/**
 * Trait LotNoByCustomFieldCheckerCreateTrait
 * @package Sam\AuctionLot\LotNo\Fill\CustomField\Validate
 */
trait LotNoByCustomFieldCheckerCreateTrait
{
    /**
     * @var LotNoByCustomFieldChecker|null
     */
    protected ?LotNoByCustomFieldChecker $lotNoByCustomFieldChecker = null;

    /**
     * @return LotNoByCustomFieldChecker
     */
    protected function createLotNoByCustomFieldChecker(): LotNoByCustomFieldChecker
    {
        return $this->lotNoByCustomFieldChecker ?: LotNoByCustomFieldChecker::new();
    }

    /**
     * @param LotNoByCustomFieldChecker $lotNoByCustomFieldChecker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotNoByCustomFieldChecker(LotNoByCustomFieldChecker $lotNoByCustomFieldChecker): static
    {
        $this->lotNoByCustomFieldChecker = $lotNoByCustomFieldChecker;
        return $this;
    }
}
