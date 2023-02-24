<?php
/**
 * SAM-10450: Decouple auction date validation logic into internal services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Hybrid;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\AuctionDateValidationHelper;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Hybrid\HybridDateValidationInput as Input;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Hybrid\HybridDateValidationResult as Result;

/**
 * Class HybridDateValidator
 * @package Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Hybrid
 */
class HybridDateValidator extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(Input $input): Result
    {
        $result = Result::new()->construct();
        $validationHelper = AuctionDateValidationHelper::new();
        if (!$input->auctionId) {
            $result = $validationHelper->checkRequired($input->startClosingDate, Result::ERR_START_CLOSING_DATE_REQUIRED, $result);
            $result = $validationHelper->checkRequired($input->biddingConsoleAccessDate, Result::ERR_BIDDING_CONSOLE_ACCESS_DATE_REQUIRED, $result);
        }
        $result = $validationHelper->checkNotEmpty($input->startClosingDate, Result::ERR_START_CLOSING_DATE_REQUIRED, $result);
        $result = $validationHelper->checkNotEmpty($input->biddingConsoleAccessDate, Result::ERR_BIDDING_CONSOLE_ACCESS_DATE_REQUIRED, $result);
        $result = $validationHelper->checkDate($input->startClosingDate, Result::ERR_START_CLOSING_DATE_INVALID, $input->mode, $result);
        $result = $validationHelper->checkDate($input->biddingConsoleAccessDate, Result::ERR_BIDDING_CONSOLE_ACCESS_DATE_INVALID, $input->mode, $result);
        $result = $validationHelper->checkDate($input->endPrebiddingDate, Result::ERR_END_PREBIDDING_DATE_INVALID, $input->mode, $result);
        if (
            !$result->hasErrorByCode(Result::ERR_START_CLOSING_DATE_INVALID)
            && !$result->hasErrorByCode(Result::ERR_BIDDING_CONSOLE_ACCESS_DATE_INVALID)
        ) {
            $result = $validationHelper->checkDateLaterThan(
                $input->biddingConsoleAccessDate,
                $input->startClosingDate,
                Result::ERR_BIDDING_CONSOLE_ACCESS_DATE_LATER_START_CLOSING_DATE,
                $input->mode,
                $result
            );
        }
        return $result;
    }
}
