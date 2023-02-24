<?php
/**
 * SAM-6684: Merge the two admin bidding histories and Improvement for Lot bidding History
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/29/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotBidHistoryForm\Edit\Save;

use Sam\Core\Constants\Admin\LotBidHistoryFormConstants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class Dto
 * @package Sam\View\Admin\Form\LotBidHistoryForm\Edit
 */
class LotBidHistoryInputDto extends CustomizableClass
{
    use NumberFormatterAwareTrait;

    // --- Incoming values ---

    public readonly string $maxBid;
    public readonly string $bidderUserId;
    public readonly string $bidAmount;

    // --- Constructors ---

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $maxBid
     * @param string $bidAmount
     * @param string $bidderUserId
     * @return $this
     */
    public function construct(string $maxBid, string $bidAmount, string $bidderUserId): static
    {
        $this->maxBid = $maxBid;
        $this->bidderUserId = $bidderUserId;
        $this->bidAmount = $bidAmount;
        return $this;
    }

    /**
     * @param array $body
     * @return $this
     */
    public function fromPsrRequest(array $body): static
    {
        $maxBid = Cast::toString($this->getNumberFormatter()->removeFormat($body[LotBidHistoryFormConstants::CID_TXT_MAX_BID]));
        $bidAmount = Cast::toString($this->getNumberFormatter()->removeFormat($body[LotBidHistoryFormConstants::CID_TXT_BID_AMOUNT]));
        $bidderUserId = (string)$body[LotBidHistoryFormConstants::CID_LST_BIDDER];
        return $this->construct($maxBid, $bidAmount, $bidderUserId);
    }
}
