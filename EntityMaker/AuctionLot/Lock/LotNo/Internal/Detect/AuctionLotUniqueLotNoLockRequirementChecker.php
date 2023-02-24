<?php
/**
 * SAM-10802: Supply uniqueness of auction lot fields: lot#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Lock\LotNo\Internal\Detect;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Lock\LotNo\Internal\Detect\Internal\Load\DataProviderCreateTrait;
use Sam\EntityMaker\AuctionLot\Lock\LotNo\Internal\Detect\AuctionLotUniqueLotNoLockRequirementCheckingResult as Result;
use Sam\AuctionLot\Load\Exception\CouldNotFindAuctionLot;
use Sam\EntityMaker\AuctionLot\Lock\LotNo\Internal\Detect\AuctionLotUniqueLotNoLockRequirementCheckingInput as Input;

/**
 * @package Sam\EntityMaker\AuctionLot
 */
class AuctionLotUniqueLotNoLockRequirementChecker extends CustomizableClass
{
    use DataProviderCreateTrait;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    public function check(Input $input): Result
    {
        $result = Result::new()->construct($input);

        if (!$input->auctionLotId) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_NEW_AUCTION_LOT_CREATED);
        }

        if ($this->isAbsentInInput($input)) {
            return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_LOT_NO_FIELDS_ABSENT_IN_INPUT);
        }

        if ($this->isEmptyInInput($input)) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_LOT_NO_MUST_BE_GENERATED);
        }

        $result = $this->willChange($input);
        return $result;
    }

    /**
     * Check if lot# fields are not assigned in input.
     * @param AuctionLotUniqueLotNoLockRequirementCheckingInput $input
     * @return bool
     */
    protected function isAbsentInInput(Input $input): bool
    {
        return !$input->isSetLotNum
            && !$input->isSetLotNumExtension
            && !$input->isSetLotNumPrefix
            && !$input->isSetLotFullNum;
    }

    /**
     * Check, if lot# fields are filled with empty values in input.
     * @param Input $input
     * @return bool
     */
    protected function isEmptyInInput(Input $input): bool
    {
        return ($input->lotNum === '' || $input->lotNum === '0')
            && ($input->lotFullNum === '' || $input->lotFullNum === '0');
    }

    /**
     * Check, if the input data must modify the existing values of lot#.
     * @param Input $input
     * @return Result
     */
    protected function willChange(Input $input): Result
    {
        $result = Result::new()->construct($input);

        $dataProvider = $this->createDataProvider();
        $auctionLot = $dataProvider->loadAuctionLot($input->auctionLotId);
        if (!$auctionLot) {
            throw CouldNotFindAuctionLot::withId($input->auctionLotId);
        }

        if (
            $input->isSetLotFullNum
            && $input->lotFullNum !== ''
        ) {
            if (!$dataProvider->validateLotNo($input->lotFullNum)) {
                return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_LOT_NO_VALIDATION_FAILED);
            }

            $lotNoParsed = $dataProvider->parseLotNo($input->lotFullNum);
            $willChange = $auctionLot->LotNum !== $lotNoParsed->lotNum
                || $auctionLot->LotNumExt !== $lotNoParsed->lotNumExtension
                || $auctionLot->LotNumPrefix !== $lotNoParsed->lotNumPrefix;
            if ($willChange) {
                return $result->addSuccess(Result::OK_LOCK_BECAUSE_CONCATENATED_LOT_NO_DIFFERS);
            }

            return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_LOT_NO_INPUT_EQUAL_TO_EXISTING);
        }

        if (
            isset($input->lotNum)
            && $auctionLot->LotNum !== Cast::toInt($input->lotNum)
        ) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_LOT_NUM_DIFFERS);
        }

        if (
            isset($input->lotNumExtension)
            && $auctionLot->LotNumExt !== $input->lotNumExtension
        ) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_LOT_NUM_EXTENSION_DIFFERS);
        }

        if (
            isset($input->lotNumPrefix)
            && $auctionLot->LotNumPrefix !== $input->lotNumPrefix
        ) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_LOT_NUM_PREFIX_DIFFERS);
        }

        return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_SEPARATED_LOT_NO_INPUT_EQUAL_TO_EXISTING);
    }
}
