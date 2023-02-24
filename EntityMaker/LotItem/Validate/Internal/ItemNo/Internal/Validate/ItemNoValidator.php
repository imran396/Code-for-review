<?php
/**
 * SAM-8833: Lot item entity maker - extract item# validation
 * SAM-6366: Corrections for Auction Lot and Lot Item Entity Makers for v3.5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Validate\Internal\ItemNo\Internal\Validate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Core\Validate\Text\TextChecker;
use Sam\EntityMaker\Base\Validate\EntityMakerPureChecker;
use Sam\EntityMaker\LotItem\Validate\Internal\ItemNo\Internal\Validate\Internal\Load\DataProvider;
use Sam\EntityMaker\LotItem\Validate\Internal\ItemNo\Internal\Validate\ItemNoValidationInput as Input;
use Sam\EntityMaker\LotItem\Validate\Internal\ItemNo\Internal\Validate\ItemNoValidationResult as Result;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Lot\ItemNo\Parse\LotItemNoParserCreateTrait;

/**
 * Class ItemNoValidator
 * @package Sam\EntityMaker\LotItem
 */
class ItemNoValidator extends CustomizableClass
{
    use LotItemNoParserCreateTrait;
    use OptionalsTrait;

    // --- Incoming values ---

    public const OP_IS_ITEM_NO_CONCATENATED = OptionalKeyConstants::KEY_IS_ITEM_NO_CONCATENATED; // bool
    public const OP_MYSQL_MAX_INT = OptionalKeyConstants::KEY_MYSQL_MAX_INT; // int
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
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param Input $input
     * @return Result
     */
    public function validate(Input $input): Result
    {
        /**
         * Fill result-object with item# inputs, we will operate on them with help of this object.
         */
        $messageGlue = (string)$this->fetchOptional(self::OP_MESSAGE_GLUE);
        $result = Result::new()->construct([], $messageGlue);

        if ($this->fetchOptional(self::OP_IS_ITEM_NO_CONCATENATED)) {
            /**
             * When we receive concatenated item# on input, then we parse it
             * and fill separated item# parts in result-object ($result->itemNum, $result->itemNumExt).
             */
            $result = $this->parseConcatenatedItemNum($input, $result);
        }

        /**
         * Quit validation, if it is already failed on parsing of concatenated item#
         */
        if ($result->hasError()) {
            return $result;
        }

        /**
         * Although item# is mandatory property of LotItem, we don't require it to be obligatory filled,
         * because system supplies auto-assignment of item# by generated value, when we create new item.
         */

        $shouldBeFilled = $this->shouldBeFilled($input);
        if ($shouldBeFilled) {
            /**
             * The check below also denies "0" input values of item#.
             */
            if (
                !$input->itemNum
                && !$input->itemFullNum // TODO: this looks redundant
            ) {
                return $result->addError(Result::ERR_ITEM_NUM_REQUIRED);
            }
        }

        /**
         * At this point we already go over the check of obligatory filled item# fields.
         * Next validations have sense only when item# inputs are not empty.
         */

        $result = $this->validateItemNumber($input, $result);
        $result = $this->validateItemNumberExtension($input, $result);
        return $result;
    }

    /**
     * Extract parts from full item# and store them in result-object.
     * @param ItemNoValidationInput $input
     * @param Result $result
     * @return Result
     */
    protected function parseConcatenatedItemNum(Input $input, Result $result): Result
    {
        if (!$input->itemFullNum) {
            $input->itemNum = '';
            $input->itemNumExt = '';
            return $result;
        }

        $itemNoParser = $this->createLotItemNoParser()->construct();
        if (!$itemNoParser->validate($input->itemFullNum)) {
            $result->addError(Result::ERR_CONCATENATED_ITEM_NO_PARSE_FAILED, $itemNoParser->getErrorMessage());
            return $result;
        }
        $itemNoParsed = $itemNoParser->parse($input->itemFullNum);
        $input->itemNum = (string)$itemNoParsed->itemNum;
        $input->itemNumExt = $itemNoParsed->itemNumExtension;
        return $result;
    }

    /**
     * Check, if item# inputs should be obligatory filled.
     * @param ItemNoValidationInput $input
     * @return bool
     */
    protected function shouldBeFilled(Input $input): bool
    {
        /**
         * If "Item#" field is not marked as required in Lot Field Config,
         * then we don't need to check inputs of item#.
         */
        if (!$this->getDataProvider()->isRequiredByLotFieldConfig($input->accountId)) {
            return false;
        }

        /**
         * If we are editing existing item
         * and item# inputs were skipped on input and do not present in input DTO,
         * then we don't need to check inputs of item#, because it shouldn't be changed.
         */
        if (
            $input->lotItemId
            && !$input->isSetItemNum
            && !$input->isSetItemFullNum
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
    protected function validateItemNumber(Input $input, Result $result): Result
    {
        /**
         * Item number validations are performed only when it is filled.
         */
        if (!$input->itemNum) {
            return $result;
        }

        /**
         * Item number cannot exceed max available integer value that is defined by DB numeric datatype.
         * Temporal coupling of business logic:
         * This check should go before check for positive integer, because it doesn't support long string numbers.
         */
        if ((int)$input->itemNum >= (int)$this->fetchOptional(self::OP_MYSQL_MAX_INT)) {
            return $result->addError(Result::ERR_ITEM_NUM_EXCEED_MAX_INTEGER);
        }

        /**
         * Item number must be positive integer.
         */
        if (!NumberValidator::new()->isIntPositive($input->itemNum)) {
            return $result->addError(Result::ERR_ITEM_NUM_NOT_POSITIVE_INTEGER);
        }

        /**
         * Check uniqueness of item# with exclusion of the current lot item.
         */
        $isFound = $this->getDataProvider()->existByItemNum(
            (int)$input->itemNum,
            $input->itemNumExt,
            $input->accountId,
            (array)$input->lotItemId
        );
        if ($isFound) {
            return $result->addError(Result::ERR_NOT_UNIQUE);
        }

        return $result;
    }

    /**
     * @param Input $input
     * @param Result $result
     * @return Result
     */
    protected function validateItemNumberExtension(Input $input, Result $result): Result
    {
        /**
         * Item number extension validations are performed only when it is filled.
         */
        if ($input->itemNumExt === '') {
            return $result;
        }

        /**
         * Item number extension format is alpha-numeric characters.
         */
        if (!TextChecker::new()->isAlphaNumeric($input->itemNumExt)) {
            return $result->addError(Result::ERR_ITEM_NUM_EXTENSION_NOT_ALPHA_NUMERIC);
        }

        /**
         * The length of the existent lot may be increased programmatically.
         * Using in the function of BuyNow lot cloning
         */
        $result->itemNumExtensionMaxLength = $input->lotItemId
            ? Constants\Lot::ITEM_NUM_EXT_MAX_LENGTH_FOR_EXISTING_LOT_ITEM
            : Constants\Lot::ITEM_NUM_EXT_MAX_LENGTH_FOR_NEW_LOT_ITEM;
        if (!EntityMakerPureChecker::new()->isLengthBetween($input->itemNumExt, 0, $result->itemNumExtensionMaxLength)) {
            return $result->addError(Result::ERR_ITEM_NUM_EXTENSION_INVALID_LENGTH);
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

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_MYSQL_MAX_INT] = $optionals[self::OP_MYSQL_MAX_INT]
            ?? static function (): int {
                return ConfigRepository::getInstance()->get('core->db->mysqlMaxInt');
            };
        $optionals[self::OP_IS_ITEM_NO_CONCATENATED] = $optionals[self::OP_IS_ITEM_NO_CONCATENATED]
            ?? static function (): int {
                return ConfigRepository::getInstance()->get('core->lot->itemNo->concatenated');
            };
        $this->setOptionals($optionals);
    }

}
