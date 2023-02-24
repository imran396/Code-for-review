<?php
/**
 * SAM-6856: Soft-deleted Auction restore
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Delete\Restore;

use AuctionCustData;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Service\CustomizableClass;

/**
 * Contains the result of the auction custom data recovery process of a soft-deleted auction
 *
 * Class AuctionCustomDataRestoreResult
 * @package Sam\CustomField\Auction\Delete\Restore
 */
class AuctionCustomDataRestoreResult extends CustomizableClass
{
    /**
     * @var AuctionCustData[]
     */
    protected array $restored = [];

    /**
     * @var ResultStatus[]
     */
    protected array $warnings = [];

    /**
     * Class instantiation method
     * @return static
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
        return $this;
    }

    /**
     * @param AuctionCustData $auctionCustData
     */
    public function addRestored(AuctionCustData $auctionCustData): void
    {
        $this->restored[] = $auctionCustData;
    }

    /**
     * @param ResultStatus $status
     */
    public function addWarning(ResultStatus $status): void
    {
        $this->warnings[] = $status;
    }

    /**
     * @return AuctionCustData[]
     */
    public function getRestored(): array
    {
        return $this->restored;
    }

    /**
     * @return ResultStatus[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }
}
