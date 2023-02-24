<?php
/**
 * SAM-8891: Auction entity-maker - Extract sale# validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\SaleNo\Internal\Validate;

use Sam\Auction\SaleNo\Parse\SaleNoParserCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Core\Validate\Text\TextChecker;
use Sam\EntityMaker\Auction\Validate\Internal\SaleNo\Internal\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\EntityMaker\Auction\Validate\Internal\SaleNo\Internal\Validate\SaleNoValidationResult as Result;
use Sam\EntityMaker\Auction\Validate\Internal\SaleNo\Internal\Validate\SaleNoValidationInput as Input;
use Sam\EntityMaker\Base\Validate\EntityMakerPureChecker;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class SaleNoValidator
 * @package Sam\EntityMaker\Auction\Validate\Internal\SaleNo\Internal\Validate
 */
class SaleNoValidator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;
    use SaleNoParserCreateTrait;

    // --- Internal ---

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Input $input
     * @return Result
     */
    public function validate(Input $input): Result
    {
        /**
         * Fill result-object with sale# inputs, we will operate on them with help of this object.
         */
        $result = Result::new()->construct();

        if ($this->cfg()->get('core->auction->saleNo->concatenated')) {
            /**
             * When we receive concatenated sale# on input, then we parse it
             * and fill separated sale# parts in input-object ($input->saleNum, $input->saleNumExt).
             */
            [$input, $result] = $this->parseConcatenatedSaleNum($input, $result);
        }

        /**
         * Quit validation, if it is already failed on parsing of concatenated sale#
         */
        if ($result->hasError()) {
            return $result;
        }

        if (
            $input->saleFullNo !== ''
            || $input->saleNum !== ''
        ) {
            $result = $this->validateSaleNumber($input, $result);
        }

        if ($input->saleNumExt) {
            $result = $this->validateSaleNumberExtension($input, $result);
        }

        if ($input->saleNum) {
            $result = $this->validateExistenceSaleNoPerAccount($input, $result);
        } else {
            $result = $this->validateSaleNoMaxAvailableValue($input, $result);
        }

        return $result;
    }

    /**
     * Extract parts from full sale# and store them in result-object.
     * @param SaleNoValidationInput $input
     * @param Result $result
     * @return array
     */
    protected function parseConcatenatedSaleNum(Input $input, Result $result): array
    {
        if (!$input->saleFullNo) {
            $input->saleNum = '';
            $input->saleNumExt = '';
            return [$input, $result];
        }

        $saleNoParser = $this->createSaleNoParser()->construct();
        if (!$saleNoParser->validate($input->saleFullNo)) {
            $result->addError(Result::ERR_SALE_FULL_NO_PARSE_ERROR, $saleNoParser->getErrorMessage());
            return [$input, $result];
        }

        $saleNoParsed = $saleNoParser->parse($input->saleFullNo);
        $input->saleNum = (string)$saleNoParsed->saleNum;
        $input->saleNumExt = $saleNoParsed->saleNumExtension;
        return [$input, $result];
    }

    /**
     * @param Input $input
     * @param Result $result
     * @return Result
     */
    protected function validateSaleNumber(Input $input, Result $result): Result
    {
        if (!NumberValidator::new()->isInt($input->saleNum)) {
            return $result->addError(Result::ERR_SALE_NUM_INVALID);
        }

        if ((int)$input->saleNum >= (int)$this->cfg()->get('core->db->mysqlMaxInt')) {
            return $result->addError(Result::ERR_SALE_NUM_HIGHER_MAX_AVAILABLE_VALUE);
        }

        return $result;
    }

    /**
     * @param Input $input
     * @param Result $result
     * @return Result
     */
    protected function validateSaleNumberExtension(Input $input, Result $result): Result
    {
        if (!EntityMakerPureChecker::new()->isLengthBetween($input->saleNumExt, 0, 3)) {
            return $result->addError(Result::ERR_SALE_NUM_EXT_INVALID);
        }

        if (!TextChecker::new()->isAlpha($input->saleNumExt)) {
            return $result->addError(Result::ERR_SALE_NUM_EXT_NOT_ALPHA);
        }

        return $result;
    }

    /**
     * @param Input $input
     * @param Result $result
     * @return Result
     */
    protected function validateExistenceSaleNoPerAccount(Input $input, Result $result): Result
    {
        if ($this->createDataProvider()->existBySaleNo(
            (int)$input->saleNum,
            $input->saleNumExt,
            (array)$input->auctionId,
            $input->accountId
        )) {
            $result->addError(Result::ERR_SALE_NO_EXIST);
        }
        return $result;
    }

    /**
     * @param Input $input
     * @param Result $result
     * @return Result
     */
    protected function validateSaleNoMaxAvailableValue(Input $input, Result $result): Result
    {
        if (
            !$input->auctionId
            && !$this->createDataProvider()->suggestSaleNo($input->accountId)
        ) {
            $result->addError(Result::ERR_SALE_NUM_HIGHER_MAX_AVAILABLE_VALUE);
        }
        return $result;
    }

}
