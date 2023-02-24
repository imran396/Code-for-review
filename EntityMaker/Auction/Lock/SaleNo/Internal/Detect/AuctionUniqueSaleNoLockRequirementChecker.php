<?php
/**
 * SAM-10615: Supply uniqueness of auction fields: sale#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Lock\SaleNo\Internal\Detect;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Load\Exception\CouldNotFindAuction;
use Sam\Auction\SaleNo\Parse\SaleNoParserCreateTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Lock\SaleNo\Internal\Detect\AuctionUniqueSaleNoLockRequirementCheckerInput as Input;
use Sam\EntityMaker\Auction\Lock\SaleNo\Internal\Detect\AuctionUniqueSaleNoLockRequirementCheckingResult as Result;

/**
 * Class AuctionUniqueSaleNoLockRequirementChecker
 * @package Sam\EntityMaker\Auction\Lock\SaleNo\Internal\Detect
 */
class AuctionUniqueSaleNoLockRequirementChecker extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use SaleNoParserCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function check(Input $input): Result
    {
        $result = Result::new()->construct($input);
        if (!$input->auctionId) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_NEW_AUCTION_CREATED);
        }
        if ($this->isAbsentInInput($input)) {
            return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_SALE_NO_FIELDS_ABSENT_IN_INPUT);
        }

        if ($this->isEmptyInInput($input)) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_SALE_NO_MUST_BE_GENERATED);
        }

        $result = $this->willChange($input);
        return $result;
    }

    /**
     * Check if sale# fields are not assigned in input.
     * @param Input $input
     * @return bool
     */
    protected function isAbsentInInput(Input $input): bool
    {
        return !$input->isSetSaleNum
            && !$input->isSetSaleNumExt
            && !$input->isSetSaleFullNo;
    }

    /**
     * Check, if sale# fields are filled with empty values in input.
     * @param Input $input
     * @return bool
     */
    protected function isEmptyInInput(Input $input): bool
    {
        $emptyValues = ['', '0', '0.0', null];
        return in_array($input->saleNum, $emptyValues, false)
            && in_array($input->saleFullNo, $emptyValues, false);
    }

    /**
     * Check, if the input data will modify the existing values of sale#.
     * @param Input $input
     * @return Result
     */
    protected function willChange(Input $input): Result
    {
        $result = Result::new()->construct($input);

        $auction = $this->getAuctionLoader()->load($input->auctionId);
        if (!$auction) {
            throw CouldNotFindAuction::withId($input->auctionId);
        }

        if (
            $input->saleFullNo !== null
            && $input->saleFullNo !== ''
        ) {
            $saleNoParser = $this->createSaleNoParser()->construct();
            if (!$saleNoParser->validate($input->saleFullNo)) {
                return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_SALE_NO_VALIDATION_FAILED);
            }

            $saleNoParsed = $saleNoParser->parse($input->saleFullNo);
            $willChange = $auction->SaleNum !== $saleNoParsed->saleNum
                || $auction->SaleNumExt !== $saleNoParsed->saleNumExtension;
            if ($willChange) {
                return $result->addSuccess(Result::OK_LOCK_BECAUSE_CONCATENATED_SALE_NO_DIFFERS);
            }

            return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_CONCATENATED_SALE_NO_INPUT_EQUAL_TO_EXISTING);
        }

        if (
            $input->saleNum
            && $auction->SaleNum !== Cast::toInt($input->saleNum)
        ) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_SALE_NUM_DIFFERS);
        }

        if (
            $input->saleNumExt
            && $auction->SaleNumExt !== $input->saleNumExt
        ) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_SALE_NUM_EXTENSION_DIFFERS);
        }

        return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_SEPARATED_SALE_NO_INPUT_EQUAL_TO_EXISTING);
    }
}
