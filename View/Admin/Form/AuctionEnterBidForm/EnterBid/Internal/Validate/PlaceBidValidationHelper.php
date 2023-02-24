<?php
/**
 * SAM-6181 : Refactor for Admin>Auction>Enter bids - Move input validation logic to separate class and implement unit test
 * https://bidpath.atlassian.net/browse/SAM-6181
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since             03/21/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionEnterBidForm\EnterBid\Internal\Validate;

use Auction;
use Sam\Core\Math\Floating;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\View\Admin\Form\AuctionEnterBidForm\EnterBid\AuctionEnterBidConstants;

/**
 * Class PlaceBidValidationHelper
 * @package Sam\View\Admin\Form\AuctionEnterBidForm\EnterBid\Internal\Validate
 */
class PlaceBidValidationHelper extends BaseValidationHelper
{
    // --- Constructors ---
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Internal logic ---

    /**
     * @return bool
     */
    public function validateBidAmount(): bool
    {
        if ($this->auctionLot) {
            $amount = $this->dto->amount;
            if ($amount === '') {
                $this->getResultStatusCollector()->addError(AuctionEnterBidConstants::ERR_BID_AMOUNT_REQUIRED);
                return false;
            }

            if (!NumberValidator::new()->isReal($amount)) {
                $this->getResultStatusCollector()->addError(AuctionEnterBidConstants::ERR_BID_AMOUNT_NUMERIC);
                return false;
            }

            if (Floating::lt($amount, 0)) {
                $this->getResultStatusCollector()->addError(AuctionEnterBidConstants::ERR_INVALID);
                return false;
            }

            if (Floating::eq($amount, 0)) {
                /** @var Auction $auction */
                $auction = $this->fetchOptional(self::OP_AUCTION);
                if ($auction->isTimed()) {
                    $this->getResultStatusCollector()->addError(AuctionEnterBidConstants::ERR_ABOVE_ZERO);
                    return false;
                }
            }
        }
        return true;
    }
}
