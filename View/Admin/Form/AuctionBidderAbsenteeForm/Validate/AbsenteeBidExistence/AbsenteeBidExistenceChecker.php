<?php
/**
 * SAM-9530: "User Absentee Bid" page - extract existing bid detection logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Validate\AbsenteeBidExistence;

use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Validate\AbsenteeBidExistence\Internal\Load\DataProviderCreateTrait;
use Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Validate\AbsenteeBidExistence\AbsenteeBidExistenceCheckingResult as Result;
use Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Validate\AbsenteeBidExistence\AbsenteeBidExistenceCheckingInput as Input;

/**
 * Class AbsenteeBidExistenceChecker
 * @package Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Validate
 */
class AbsenteeBidExistenceChecker extends CustomizableClass
{
    use DataProviderCreateTrait;
    use LotRendererAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function exist(Input $input): Result
    {
        $result = Result::new()->construct();
        if (!$input->auctionId) {
            return $result->addError(Result::ERR_AUCTION_ID_UNDEFINED);
        }

        if (!$input->userId) {
            return $result->addError(Result::ERR_USER_ID_UNDEFINED);
        }

        $lotNoParsed = LotNoParsed::new()->construct(
            Cast::toInt($input->lotNum),
            $input->lotNumExt,
            $input->lotNumPrefix
        );
        if (!$lotNoParsed->ok()) {
            return $result->addErrorWithAppendedMessage(
                Result::ERR_LOT_NO_INCORRECT,
                composeSuffix(['lot#' => $this->getLotRenderer()->makeLotNo($input->lotNum, $input->lotNumExt, $input->lotNumPrefix)])
            );
        }

        $dataProvider = $this->createDataProvider();

        $lotItemId = $dataProvider->loadLotItemId($lotNoParsed, $input->auctionId, true);
        if (!$lotItemId) {
            return $result->addErrorWithAppendedMessage(
                Result::ERR_LOT_NOT_FOUND_BY_LOT_NO,
                composeSuffix(['lot#' => $this->getLotRenderer()->makeLotNoByParsed($lotNoParsed)])
            );
        }

        $isFoundAbsenteeBid = $dataProvider->existAbsenteeBid(
            $lotItemId,
            $input->auctionId,
            $input->userId,
            true
        );
        if (!$isFoundAbsenteeBid) {
            return $result->addInfo(Result::INFO_ABSENTEE_BID_NOT_FOUND);
        }

        return $result->addSuccess(Result::OK_ABSENTEE_BID_FOUND);
    }

}
