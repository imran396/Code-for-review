<?php
/**
 * SAM-10450: Decouple auction date validation logic into internal services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Common;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\AuctionDateValidationHelper;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Common\CommonDateValidationInput as Input;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Common\CommonDateValidationResult as Result;

/**
 * Class CommonDateValidator
 * @package Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Common
 */
class CommonDateValidator extends CustomizableClass
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

        $result = $validationHelper->checkDate($input->publishDate, Result::ERR_PUBLISH_DATE_INVALID, $input->mode, $result);
        $result = $validationHelper->checkDate($input->unpublishDate, Result::ERR_UNPUBLISH_DATE_INVALID, $input->mode, $result);
        $result = $validationHelper->checkDate($input->startRegisterDate, Result::ERR_START_REGISTER_DATE_INVALID, $input->mode, $result);
        $result = $validationHelper->checkDate($input->endRegisterDate, Result::ERR_END_REGISTER_DATE_INVALID, $input->mode, $result);
        $result = $validationHelper->checkDate($input->startBiddingDate, Result::ERR_START_BIDDING_DATE_INVALID, $input->mode, $result);
        if (
            !$result->hasErrorByCode(Result::ERR_START_REGISTER_DATE_INVALID)
            && !$result->hasErrorByCode(Result::ERR_END_REGISTER_DATE_INVALID)
        ) {
            $result = $validationHelper->checkDateLaterThan(
                $input->startRegisterDate,
                $input->endRegisterDate,
                Result::ERR_END_REGISTER_DATE_EARLIER_START_REGISTER_DATE,
                $input->mode,
                $result
            );
        }
        return $result;
    }
}
