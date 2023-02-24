<?php
/**
 * SAM-9530: "User Absentee Bid" page - extract logic and cover with unit test for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Validate;

use DateTime;
use Sam\AuctionLot\LotNo\Parse\LotNoParserCreateTrait;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderChecker;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Common\AuctionBidderAbsenteeBidEditingInput as Input;
use Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Validate\AuctionBidderAbsenteeBidValidationResult as Result;
use Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Validate\Internal\Load\DataProviderCreateTrait;

/**
 * Class AuctionBidderAbsenteeBidValidator
 * @package Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Validate
 */
class AuctionBidderAbsenteeBidValidator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;
    use LotNoParserCreateTrait;
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check validate
     * @param Input $input
     * @return Result
     */
    public function validate(Input $input): Result
    {
        $result = Result::new();
        $result->initResult();
        $dataProvider = $this->createDataProvider();

        $isAuctionRegistered = $dataProvider->isAuctionRegistered($input->userId, $input->auctionId);
        if (!$isAuctionRegistered) {
            $result->addError($result::ERR_BIDDER_REMOVED);
            return $result;
        }

        $isAuctionApproved = $dataProvider->isAuctionApproved($input->userId, $input->auctionId, [AuctionBidderChecker::OP_CHECK_NAA => false]);
        if (!$isAuctionApproved) {
            $result->addError($result::ERR_BIDDER_NOT_APPROVED);
            return $result;
        }

        $result = $this->validateLotNum($input, $result);
        $result = $this->validateMaxBid($input, $result);
        $result = $this->validateDate($input, $result);

        return $result;
    }

    /**
     * @param Input $input
     * @param Result $result
     * @return Result
     */
    protected function validateLotNum(Input $input, Result $result): Result
    {
        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $lotFullNum = trim((string)$input->lotFullNum);
            if (!$lotFullNum) {
                $result->addError($result::ERR_LOT_FULL_NUM_REQUIRED);
                return $result;
            }

            $lotNoParser = $this->createLotNoParser()->construct();
            $isValid = $lotNoParser->validate($lotFullNum);
            if (!$isValid) {
                $result->addError(
                    $result::ERR_LOT_FULL_NUM_INVALID,
                    $lotNoParser->getErrorMessage()
                );
                return $result;
            }
            $lotNoParsed = $lotNoParser->parse($lotFullNum);
            $input->setLotNum((string)$lotNoParsed->lotNum);
            $input->setLotNumPrefix($lotNoParsed->lotNumPrefix);
            $input->setLotNumExt($lotNoParsed->lotNumExtension);
        } else {
            if (!$input->absenteeBidId) {
                $lotNumPrefix = trim((string)$input->lotNumPrefix);
                $lotNum = trim((string)$input->lotNum);
                $lotNumExt = trim((string)$input->lotNumExt);
                if ($lotNum === '') {
                    $result->addError($result::ERR_LOT_NUM_REQUIRED);
                    return $result;
                }

                if (!preg_match('/\d+/', $lotNum)) {
                    $result->addError($result::ERR_LOT_NUM_INVALID);
                    return $result;
                }

                if (preg_match('/[^a-zA-Z0-9]/', $lotNumPrefix)) {
                    $result->addError($result::ERR_LOT_NUM_PREFIX);
                    return $result;
                }

                if (preg_match('/[^a-zA-Z0-9]/', $lotNumExt)) {
                    $result->addError($result::ERR_LOT_NUM_EXT);
                    return $result;
                }
            }
        }

        if ($this->cfg()->get('core->db->mysqlMaxInt') < $input->lotNum) {
            if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
                return $result->addError($result::ERR_LOT_FULL_NUM_TOO_BIG);
            }

            return $result->addError($result::ERR_LOT_NUM_TOO_BIG);
        }

        $isFoundByLotNo = $this->createDataProvider()->existByLotNo(
            $input->auctionId,
            (int)$input->lotNum,
            $input->lotNumExt,
            $input->lotNumPrefix,
            true
        );

        if (!$isFoundByLotNo) {
            if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
                return $result->addError($result::ERR_LOT_FULL_NUM_NOT_EXISTS);
            }

            return $result->addError($result::ERR_LOT_NUM_NOT_EXISTS);
        }

        return $result;
    }

    /**
     * @param Input $input
     * @param Result $result
     * @return Result
     */
    protected function validateMaxBid(Input $input, Result $result): Result
    {
        if ($input->bidType !== Constants\Bid::ABT_REGULAR) {
            return $result;
        }

        $maxBid = $this->getNumberFormatter()->removeFormat($input->maxBid);
        if ($maxBid === '') {
            $result->addError($result::ERR_MAX_BID_REQUIRED);
            return $result;
        }

        if (!NumberValidator::new()->isRealPositive($maxBid)) {
            $result->addError($result::ERR_MAX_BID_INVALID);
            return $result;
        }

        return $result;
    }

    /**
     * @param Input $input
     * @param Result $result
     * @return Result
     */
    protected function validateDate(Input $input, Result $result): Result
    {
        $date = $input->getBidDateUtc();
        if (!$date instanceof DateTime) {
            $result->addError($result::ERR_DATE_REQUIRED);
            return $result;
        }

        return $result;
    }
}
