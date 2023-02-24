<?php
/**
 * SAM-8892: Auction Lot entity maker - extract lot# validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo\Internal\Validate;

use Sam\AuctionLot\LotNo\Parse\LotNoParserCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Core\Validate\Text\TextChecker;
use Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo\Internal\Validate\Internal\Load\DataProvider;
use Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo\Internal\Validate\LotNoValidationInput as Input;
use Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo\Internal\Validate\LotNoValidationResult as Result;
use Sam\EntityMaker\Base\Validate\EntityMakerPureChecker;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\LotFieldConfigProviderAwareTrait;

/**
 * Class LotNoValidator
 * @package Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo\Internal\Validate
 */
class LotNoValidator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use LotFieldConfigProviderAwareTrait;
    use LotNoParserCreateTrait;
    use OptionalsTrait;

    // --- Incoming values ---

    public const OP_MESSAGE_GLUE = OptionalKeyConstants::KEY_MESSAGE_GLUE; // string

    // --- Internal ---

    /** @var DataProvider|null */
    protected ?DataProvider $dataProvider = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->setOptionals($optionals);
        return $this;
    }

    /**
     * @param Input $input
     * @return Result
     */
    public function validate(Input $input): Result
    {
        $messageGlue = (string)$this->fetchOptional(self::OP_MESSAGE_GLUE);
        $result = Result::new()->construct([], $messageGlue);

        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            /**
             * When we receive concatenated lot# on input, then we parse it
             * and fill separated lot# parts in result-object ($result->lotNum, $result->lotNumExt, $result->lotNumPrefix).
             */
            [$input, $result] = $this->parseConcatenatedLotNum($input, $result);
        }

        /**
         * Quit validation, if it is already failed on parsing of concatenated lot#
         */
        if ($result->hasError()) {
            return $result;
        }

        /**
         * Although lot# is mandatory property of AuctionLotItem, we don't require it to be obligatory filled,
         * because system supplies auto-assignment of lot# by generated value, when we create new auction lot.
         */

        $shouldBeFilled = $this->shouldBeFilled($input);
        if ($shouldBeFilled) {
            if ($input->lotNum === '') {
                return $result->addError(Result::ERR_LOT_NUM_REQUIRED);
            }
        }

        /**
         * At this point we already go over the check of obligatory filled lot# fields.
         * Next validations have sense only when lot# inputs are not empty.
         */

        $result = $this->validateLotNumber($input, $result);
        $result = $this->validateLotNumberExtension($input, $result);
        return $result;
    }

    /**
     * Extract parts from full lot# and store them in input-object.
     * @param LotNoValidationInput $input
     * @param Result $result
     * @return array [Input, Result]
     */
    protected function parseConcatenatedLotNum(Input $input, Result $result): array
    {
        if ($input->lotFullNum === '') {
            $input->lotNum = '';
            $input->lotNumExt = '';
            $input->lotNumPrefix = '';
            return [$input, $result];
        }

        $lotNoParser = $this->createLotNoParser()->construct();
        if (!$lotNoParser->validate($input->lotFullNum)) {
            $result->addError(Result::ERR_CONCATENATED_LOT_NO_PARSE_FAILED, $lotNoParser->getErrorMessage());
            return [$input, $result];
        }
        $lotNoParsed = $lotNoParser->parse($input->lotFullNum);
        $input->lotNum = (string)$lotNoParsed->lotNum;
        $input->lotNumPrefix = $lotNoParsed->lotNumPrefix;
        $input->lotNumExt = $lotNoParsed->lotNumExtension;
        return [$input, $result];
    }

    /**
     * Check, if lot# inputs should be obligatory filled.
     * @param LotNoValidationInput $input
     * @return bool
     */
    protected function shouldBeFilled(Input $input): bool
    {
        /**
         * If "Lot#" field is not marked as required in Lot Field Config,
         * then we don't need to check inputs of lot#.
         */
        if (!$this->getDataProvider()->isRequiredByLotFieldConfig($input->accountId)) {
            return false;
        }
        /**
         * If we are editing existing auction lot in "Open Field Set" mode (SOAP, CSV),
         * and lot# inputs were skipped on input and do not present in input DTO,
         * then we don't need to check inputs of lot#, because it shouldn't be changed.
         */
        if (
            $input->id
            && !$input->isLotNumAssigned
            && !$input->isLotFullNumAssigned
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param Input $input
     * @param Result $result
     * @return Result
     */
    protected function validateLotNumber(Input $input, Result $result): Result
    {
        /**
         * Lot number validations are performed only when it is filled.
         */
        if ($input->lotNum === '') {
            return $result;
        }

        /**
         * Lot number cannot exceed max available integer value that is defined by DB numeric datatype.
         * Temporal coupling of business logic:
         * This check should go before check for positive integer, because it doesn't support long string numbers.
         */
        if ((int)$input->lotNum >= (int)$this->cfg()->get('core->db->mysqlMaxInt')) {
            return $result->addError(Result::ERR_LOT_NUM_HIGHER_MAX_AVAILABLE_VALUE);
        }

        /**
         * Lot number must be positive integer or zero.
         */
        if (!NumberValidator::new()->isIntPositiveOrZero($input->lotNum)) {
            return $result->addError(Result::ERR_LOT_NUM_INVALID);
        }

        /**
         * Check uniqueness of lot# independently of "overwriting" option.
         */
        // Disabled in SAM-10802: Supply uniqueness of auction lot fields: lot#
        // $this->validateLotNumberExistence($input, $result);

        return $result;
    }

    /**
     * @param LotNoValidationInput $input
     * @param LotNoValidationResult $result
     * @return LotNoValidationResult
     */
    protected function validateLotNumberExistence(Input $input, Result $result): Result
    {
        $isFound = $this->getDataProvider()->existByLotNum(
            (int)$input->auctionId,
            (int)$input->lotNum,
            $input->lotNumExt,
            $input->lotNumPrefix,
            (array)$input->id
        );
        if ($isFound) {
            return $result->addError(Result::ERR_LOT_NUM_EXIST);
        }

        return $result;
    }

    /**
     * @param Input $input
     * @param Result $result
     * @return Result
     */
    protected function validateLotNumberExtension(Input $input, Result $result): Result
    {
        /**
         * Lot number extension validations are performed only when it is filled.
         */
        if ($input->lotNumExt === '') {
            return $result;
        }

        /**
         * Lot number extension format is alpha-numeric characters.
         */
        if (!TextChecker::new()->isAlphaNumeric($input->lotNumExt)) {
            return $result->addError(Result::ERR_LOT_NUM_EXT_INVALID);
        }

        /**
         * The length of the existent lot may be increased programmatically.
         * Using in the function of BuyNow lot cloning
         */
        $result->lotNoMaxLength = $input->id
            ? Constants\Lot::LOT_NUM_EXT_MAX_LENGTH_FOR_EXISTING_LOT
            : Constants\Lot::LOT_NUM_EXT_MAX_LENGTH_FOR_NEW_LOT;
        if (!EntityMakerPureChecker::new()->isLengthBetween($input->lotNumExt, 0, $result->lotNoMaxLength)) {
            return $result->addError(
                Result::ERR_LOT_NUM_EXT_INVALID_LENGTH,
                sprintf(Result::ERROR_MESSAGES[Result::ERR_LOT_NUM_EXT_INVALID_LENGTH], $result->lotNoMaxLength)
            );
        }

        return $result;
    }

    /**
     * @param DataProvider $dataProvider
     * @return $this
     */
    public function setDataProvider(DataProvider $dataProvider): static
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }

    /**
     * @return DataProvider
     */
    protected function getDataProvider(): DataProvider
    {
        if ($this->dataProvider === null) {
            $this->dataProvider = DataProvider::new();
        }
        return $this->dataProvider;
    }
}
