<?php
/**
 * Class for validating of Lot Category input data
 *
 * SAM-4048: LotCategory entity maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 5, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotCategory\Validate;

use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Math\Floating;
use Sam\Core\Platform\Constant\Base\ConstantNameResolver;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\EntityMaker\Base\Validate\BaseMakerValidator;
use Sam\EntityMaker\LotCategory\Common\LotCategoryMakerCustomFieldManager;
use Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerConfigDto;
use Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerDtoHelperAwareTrait;
use Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerInputDto;
use Sam\EntityMaker\LotCategory\Validate\Constants\ResultCode;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Category\Order\LotCategoryOrdererAwareTrait;
use Sam\Lot\Category\Validate\LotCategoryExistenceCheckerAwareTrait;
use SplFileInfo;

/**
 * Class LotCategoryMakerValidator
 * @package Sam\EntityMaker\LotCategory
 *
 * The following methods are handled by \Sam\EntityMaker\Base\Validator::__call() method:
 * GetErrorMessage Methods
 * @method getBuyNowAmountErrorMessage()
 * @method getCcErrorMessage()
 * @method getImageLinkErrorMessage()
 * @method getNameErrorMessage()
 * @method getNestingLevelErrorMessage()
 * @method getParentErrorMessage()
 * @method getQuantityDigitsErrorMessage()
 * @method getStartingBidErrorMessage()
 * HasError Methods
 * @method hasBuyNowAmountError()
 * @method hasCcError()
 * @method hasImageLinkError()
 * @method hasNameError()
 * @method hasNestingLevelError()
 * @method hasParentError()
 * @method hasQuantityDigitsError()
 * @method hasStartingBidError()
 *
 * @method LotCategoryMakerInputDto getInputDto()
 * @method LotCategoryMakerConfigDto getConfigDto()
 *
 * @property LotCategoryMakerCustomFieldManager $customFieldManager
 */
class LotCategoryMakerValidator extends BaseMakerValidator
{
    use LotCategoryExistenceCheckerAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotCategoryMakerDtoHelperAwareTrait;
    use LotCategoryOrdererAwareTrait;
    use LotCustomFieldLoaderCreateTrait;

    /** @var string[] */
    protected array $tagNames = [
        ResultCode::BUY_NOW_AMOUNT_NOT_POSITIVE_NUMBER => 'BuyNowAmount',
        ResultCode::CONSIGNMENT_COMMISSION_NOT_POSITIVE_OR_ZERO_NUMBER => 'ConsignmentCommission',
        ResultCode::CUSTOM_FIELD_DECIMAL_ERROR => 'CustomField',
        ResultCode::CUSTOM_FIELD_INTEGER_ERROR => 'CustomField',
        ResultCode::CUSTOM_FIELD_SELECT_INVALID_OPTION_ERROR => 'CustomField',
        ResultCode::INVALID_IMAGE_EXTENSION => 'ImageLink',
        ResultCode::INVALID_NESTED_LEVEL => 'ParentId',
        ResultCode::INVALID_PARENT => 'ParentId',
        ResultCode::INVALID_POSITION => 'Name',
        ResultCode::NAME_LENGTH_LIMIT => 'Name',
        ResultCode::NAME_NOT_UNIQUE => 'Name',
        ResultCode::NAME_REQUIRED => 'Name',
        ResultCode::PARENT_CATEGORY_AMONG_DESCENDANTS => 'ParentName',
        ResultCode::QUANTITY_DIGITS_INVALID => 'QuantityDigits',
        ResultCode::STARTING_BID_NOT_POSITIVE_NUMBER => 'StartingBid',
    ];

    /** @var string[] */
    protected array $errorMessages = [
        ResultCode::BUY_NOW_AMOUNT_NOT_POSITIVE_NUMBER => 'Should be positive numeric',
        ResultCode::CONSIGNMENT_COMMISSION_NOT_POSITIVE_OR_ZERO_NUMBER => 'Should be positive or zero numeric',
        ResultCode::CUSTOM_FIELD_DECIMAL_ERROR => 'Should be numeric',
        ResultCode::CUSTOM_FIELD_INTEGER_ERROR => 'Should be numeric integer',
        ResultCode::CUSTOM_FIELD_SELECT_INVALID_OPTION_ERROR => 'Has invalid option',
        ResultCode::INVALID_IMAGE_EXTENSION => 'File type must be a JPG, PNG or GIF',
        ResultCode::INVALID_NESTED_LEVEL => 'Result nesting level too deep',
        ResultCode::INVALID_PARENT => 'Invalid',
        ResultCode::INVALID_POSITION => 'Invalid position',
        ResultCode::NAME_LENGTH_LIMIT => 'Maximum length reached',
        ResultCode::NAME_NOT_UNIQUE => 'Already exist',
        ResultCode::NAME_REQUIRED => 'Required',
        ResultCode::PARENT_CATEGORY_AMONG_DESCENDANTS => 'New parent category is among child categories',
        ResultCode::QUANTITY_DIGITS_INVALID => 'Should be integer between 0 and ' . Constants\Lot::LOT_QUANTITY_MAX_FRACTIONAL_DIGITS,
        ResultCode::STARTING_BID_NOT_POSITIVE_NUMBER => 'Should be positive numeric',
    ];

    /** @var string[] */
    public array $imageMimeTypes = ['jpg', 'png', 'gif'];
    /** @var LotItemCustField[] */
    private ?array $customFields = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotCategoryMakerInputDto $inputDto
     * @param LotCategoryMakerConfigDto $configDto
     * @return $this
     */
    public function construct(
        LotCategoryMakerInputDto $inputDto,
        LotCategoryMakerConfigDto $configDto
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        $this->customFieldManager = LotCategoryMakerCustomFieldManager::new()->construct($inputDto, $configDto);
        $this->getLotCategoryMakerDtoHelper()->construct($configDto->mode);
        return $this;
    }

    /**
     * Validate data
     * @return bool
     */
    public function validate(): bool
    {
        $inputDto = $this->getLotCategoryMakerDtoHelper()->prepareValues($this->getInputDto(), $this->getConfigDto());
        $this->setInputDto($inputDto);
        $configDto = $this->getConfigDto();

        if (!$configDto->mode->isWebAdmin()) {
            $this->addTagNamesToErrorMessages();
        }

        $this->validateName();
        $this->validateBuyNowAmount();
        $this->validateStartingBid();
        $this->validateCc();
        $this->validateImageLink();
        $this->validateParent();
        $this->validateNestingLevel();
        $this->validateCustomFields();
        $this->validateQuantityDigits();

        $this->log();
        $isValid = empty($this->errors) && empty($this->customFieldsErrors);
        $configDto->enableValidStatus($isValid);
        return $isValid;
    }

    /** GetErrors Methods */

    /**
     * Get lot category name errors
     * @return array
     */
    public function getBuyNowAmountErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::BUY_NOW_AMOUNT_NOT_POSITIVE_NUMBER]);
        return $intersected;
    }

    /**
     * Get consignment commission errors
     * @return array
     */
    public function getCcErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::CONSIGNMENT_COMMISSION_NOT_POSITIVE_OR_ZERO_NUMBER]);
        return $intersected;
    }

    /**
     * Get lot category image link errors
     * @return array
     */
    public function getImageLinkErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::INVALID_IMAGE_EXTENSION
            ]
        );
        return $intersected;
    }

    /**
     * Get account name errors
     * @return array
     */
    public function getNameErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::NAME_REQUIRED,
                ResultCode::NAME_NOT_UNIQUE,
                ResultCode::NAME_LENGTH_LIMIT,
            ]
        );
        return $intersected;
    }

    /**
     * Get nesting level errors
     * @return array
     */
    public function getNestingLevelErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::INVALID_NESTED_LEVEL,
            ]
        );
        return $intersected;
    }

    /**
     * Get parent errors
     * @return array
     */
    public function getParentErrors(): array
    {
        $intersected = array_intersect(
            $this->errors,
            [
                ResultCode::PARENT_CATEGORY_AMONG_DESCENDANTS,
            ]
        );
        return $intersected;
    }

    /**
     * Get lot category starting bid errors
     * @return array
     */
    public function getStartingBidErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::STARTING_BID_NOT_POSITIVE_NUMBER]);
        return $intersected;
    }

    /**
     * Get lot quantity digits errors
     * @return array
     */
    public function getQuantityDigitsErrors(): array
    {
        $intersected = array_intersect($this->errors, [ResultCode::QUANTITY_DIGITS_INVALID]);
        return $intersected;
    }

    /**
     * Do the customFields fields have an error
     * @return bool
     */
    public function hasCustomFieldsErrors(): bool
    {
        $has = !empty($this->getCustomFieldsErrors());
        return $has;
    }

    /**
     * Get customFields errors
     * @return array
     */
    public function getCustomFieldsErrors(): array
    {
        return $this->customFieldsErrors;
    }

    /**
     * Support logging of found errors or success
     */
    protected function log(): void
    {
        $inputDto = $this->getInputDto();
        if (empty($this->errors)) {
            log_trace('Lot category validation done' . composeSuffix(['lic' => $inputDto->id]));
        } else {
            // detect names of constants for error codes
            [$foundNamesToCodes, $notFoundCodes] = ConstantNameResolver::new()
                ->construct()
                ->resolveManyFromClass($this->errors, ResultCode::class);

            $foundNamesWithCodes = array_map(
                static function ($v) {
                    return "{$v[1]} ({$v[0]})";
                },
                $foundNamesToCodes
            );
            $logData = [
                'lic' => $inputDto->id,
                'errors' => array_merge(array_values($foundNamesWithCodes), $notFoundCodes),
            ];
            log_debug('Lot category validation failed' . composeSuffix($logData));
        }
    }

    /* LotCategory validation rules */

    /**
     * @return void
     */
    protected function validateName(): void
    {
        $this->validateNameRequired();
        $this->validateNameNotUnique();
        $this->validateNameLengthLimit();
    }

    /**
     * Check for name required
     * @return void
     */
    protected function validateNameRequired(): void
    {
        $this->checkRequired('name', ResultCode::NAME_REQUIRED);
    }

    /**
     * Check for name unique
     * @return void
     */
    protected function validateNameNotUnique(): void
    {
        $inputDto = $this->getInputDto();
        if ($this->getLotCategoryExistenceChecker()->existByName((string)$inputDto->name, [$inputDto->id])) {
            $this->addError(ResultCode::NAME_NOT_UNIQUE);
        }
    }

    /**
     * @return void
     */
    protected function validateNameLengthLimit(): void
    {
        $inputDto = $this->getInputDto();
        if (strlen((string)$inputDto->name) > $this->cfg()->get('core->lot->category->name->lengthLimit')) {
            $this->addError(ResultCode::NAME_LENGTH_LIMIT);
        }
    }

    /**
     * @return void
     */
    protected function validateBuyNowAmount(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (trim((string)$inputDto->buyNowAmount) === '') {
            return;
        }

        $buyNowAmount = $configDto->mode->isSoap()
            ? $inputDto->buyNowAmount
            : $this->getNumberFormatter()->parse($inputDto->buyNowAmount);
        if (
            !is_numeric($buyNowAmount)
            || Floating::lteq($buyNowAmount, 0.)
        ) {
            $this->addError(ResultCode::BUY_NOW_AMOUNT_NOT_POSITIVE_NUMBER);
        }
    }

    /**
     * @return void
     */
    protected function validateStartingBid(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (trim((string)$inputDto->startingBid) === '') {
            return;
        }

        $startingBid = $configDto->mode->isSoap()
            ? $inputDto->startingBid
            : $this->getNumberFormatter()->parse($inputDto->startingBid);
        if (
            !is_numeric($startingBid)
            || Floating::lteq($startingBid, 0)
        ) {
            $this->addError(ResultCode::STARTING_BID_NOT_POSITIVE_NUMBER);
        }
    }

    /**
     * Check Consignment Commission field should be positive
     * @return void
     */
    protected function validateCc(): void
    {
        $inputDto = $this->getInputDto();
        $consignmentCommission = (string)$inputDto->consignmentCommission;
        if (
            $consignmentCommission !== ''
            && (
                !is_numeric($consignmentCommission)
                || Floating::lt($consignmentCommission, 0)
            )
        ) {
            $this->addError(ResultCode::CONSIGNMENT_COMMISSION_NOT_POSITIVE_OR_ZERO_NUMBER);
        }
    }

    /**
     * Check Lot Category Image extensions
     * @return void
     */
    protected function validateImageLink(): void
    {
        $inputDto = $this->getInputDto();
        $imageLink = $inputDto->imageLink;
        if ($imageLink) {
            $file = new SplFileInfo($imageLink);
            $extension = $file->getExtension();
            if (!in_array(strtolower($extension), $this->imageMimeTypes, true)) {
                $this->addError(ResultCode::INVALID_IMAGE_EXTENSION);
            }
        }
    }

    /**
     * Check quantity digits value
     */
    protected function validateQuantityDigits(): void
    {
        $this->checkInteger('quantityDigits', ResultCode::QUANTITY_DIGITS_INVALID);
        $this->checkBetween('quantityDigits', ResultCode::QUANTITY_DIGITS_INVALID, 0, Constants\Lot::LOT_QUANTITY_MAX_FRACTIONAL_DIGITS);
    }

    /**
     * Check customFields
     */
    public function validateCustomFields(): void
    {
        $customValues = $this->customFieldManager->getCustomFieldValues();
        foreach ($this->getAllPossibleCustomFields() as $customField) {
            $value = $customValues[$customField->Id] ?? null;

            if ($value) {
                switch ($customField->Type) {
                    case Constants\CustomField::TYPE_INTEGER:
                        $this->checkCustomFieldInteger($customField, $value);
                        break;
                    case Constants\CustomField::TYPE_DECIMAL:
                        $this->checkCustomFieldDecimal($customField, $value);
                        break;
                }
            }
        }
    }


    /**
     * Check customField of type integer
     * @param LotItemCustField $lotCustomField
     * @param string $value
     */
    protected function checkCustomFieldInteger(LotItemCustField $lotCustomField, string $value): void
    {
        if (!NumberValidator::new()->isInt($value)) {
            $this->addError(ResultCode::CUSTOM_FIELD_INTEGER_ERROR, '', $lotCustomField);
        }
    }

    /**
     * Check customField of type decimal
     * @param LotItemCustField $lotCustomField
     * @param string $value
     */
    protected function checkCustomFieldDecimal(LotItemCustField $lotCustomField, string $value): void
    {
        if (!$this->getConfigDto()->mode->isSoap()) {
            $value = $this->getNumberFormatter()->removeFormat($value);
        }
        if (!NumberValidator::new()->isReal($value)) {
            $this->addError(ResultCode::CUSTOM_FIELD_DECIMAL_ERROR, '', $lotCustomField);
        }
    }

    /**
     * Check new parent category could not be inside children
     * @param int|null $lotCategoryId
     * @param int|null $newParentCategoryId
     * @return bool
     */
    protected function checkNewParentNotAmongChildren(?int $lotCategoryId, ?int $newParentCategoryId): bool
    {
        $newParentCategoryId = (int)$newParentCategoryId;
        $isValidParent = true;
        //If category already exists
        if ($lotCategoryId) {
            $childLotCategories = $this->getLotCategoryLoader()->loadChildrenOfCategoryId($lotCategoryId);
            foreach ($childLotCategories as $childLotCategory) {
                if ($childLotCategory->Id === $newParentCategoryId) {
                    $isValidParent = false;
                    break;
                }

                $isValidParent = $this->checkNewParentNotAmongChildren($childLotCategory->Id, $newParentCategoryId);
                if (!$isValidParent) {
                    break;
                }
            }
        }
        return $isValidParent;
    }

    /**
     * Checking to not exceed the level of nesting categories
     * @return void
     */
    protected function validateNestingLevel(): void
    {
        $inputDto = $this->getInputDto();
        $isLevelOk = true;
        $parentCategory = $this->getLotCategoryLoader()->load((int)$inputDto->parentId);

        if ($parentCategory) {
            $lotCategoryId = $inputDto->id;
            $maxLevel = $this->getLotCategoryOrderer()->getMaxLevel();
            //If LotCategory already exists and has child
            if (
                $lotCategoryId
                && $this->getLotCategoryExistenceChecker()->hasDescendants($lotCategoryId)
            ) {
                $lotCategoryId = Cast::toInt($lotCategoryId, Constants\Type::F_INT_POSITIVE);
                $childLevelDepth = $this->getLotCategoryOrderer()->getChildLevelDepth($lotCategoryId);
                $isLevelOk = ($childLevelDepth + $parentCategory->Level) <= $maxLevel;
            } else {
                //If we create new LotCategory or move single category without child
                $isLevelOk = ($parentCategory->Level + 1) < $maxLevel;
            }
        }

        if (!$isLevelOk) {
            $this->addError(ResultCode::INVALID_NESTED_LEVEL);
        }
    }

    /**
     * Checking new parent category
     * @return void
     */
    protected function validateParent(): void
    {
        $this->checkParentByName();
        $this->checkPosition();
        $this->validateParentById();
    }

    /**
     * @return void
     */
    protected function validateParentById(): void
    {
        $inputDto = $this->getInputDto();
        $lotCategoryId = (int)$inputDto->id;
        $parentCategoryId = (int)$inputDto->parentId;
        if ($parentCategoryId) {
            if (!NumberValidator::new()->isReal($parentCategoryId)) {
                $this->addError(ResultCode::INVALID_PARENT);
                return;
            }

            if (!$this->checkNewParentNotAmongChildren($lotCategoryId, $parentCategoryId)) {
                $this->addError(ResultCode::PARENT_CATEGORY_AMONG_DESCENDANTS);
            }
        }
    }

    /**
     * Set custom fields
     * @param LotItemCustField[] $customFields
     */
    public function setCustomFields(array $customFields): void
    {
        $this->customFields = $customFields;
    }

    /**
     * @return LotItemCustField[]
     */
    private function getAllPossibleCustomFields(): array
    {
        if (!$this->customFields) {
            $this->customFields = $this->createLotCustomFieldLoader()->loadAll();
        }
        return $this->customFields;
    }

    /**
     * Checking parent by Name
     * @return void
     */
    protected function checkParentByName(): void
    {
        $inputDto = $this->getInputDto();
        if (isset($inputDto->parentName)) {
            $parentCategory = $this->getLotCategoryLoader()->loadByName((string)$inputDto->parentName);
            if (!$parentCategory) {
                $this->addError(ResultCode::INVALID_PARENT);
            } elseif (!$this->checkNewParentNotAmongChildren($inputDto->id, $parentCategory->Id)) {
                $this->addError(ResultCode::PARENT_CATEGORY_AMONG_DESCENDANTS);
            }
        }
    }

    /**
     * @return void
     */
    protected function checkPosition(): void
    {
        $this->checkBeforeId();
        $this->checkAfterId();
        $this->checkBeforeName();
        $this->checkAfterName();
    }

    /**
     * @return void
     */
    protected function checkBeforeId(): void
    {
        $inputDto = $this->getInputDto();
        if (isset($inputDto->beforeId)) {
            if (!NumberValidator::new()->isReal($inputDto->beforeId)) {
                $this->addError(ResultCode::INVALID_POSITION);
                return;
            }
            $beforeLotCategory = $this->getLotCategoryLoader()->load((int)$inputDto->beforeId);
            if (!$beforeLotCategory) {
                $this->addError(ResultCode::INVALID_POSITION);
                return;
            }
        }
    }

    /**
     * @return void
     */
    protected function checkAfterId(): void
    {
        $inputDto = $this->getInputDto();
        if (isset($inputDto->afterId)) {
            if (!NumberValidator::new()->isReal($inputDto->afterId)) {
                $this->addError(ResultCode::INVALID_POSITION);
                return;
            }
            $afterLotCategory = $this->getLotCategoryLoader()->load((int)$inputDto->afterId);
            if (!$afterLotCategory) {
                $this->addError(ResultCode::INVALID_POSITION);
                return;
            }
        }
    }

    /**
     * @return void
     */
    protected function checkBeforeName(): void
    {
        $inputDto = $this->getInputDto();
        if (isset($inputDto->beforeName)) {
            if ((string)$inputDto->beforeName === '') {
                $this->addError(ResultCode::INVALID_POSITION);
                return;
            }
            $beforeLotCategory = $this->getLotCategoryLoader()->loadByName((string)$inputDto->beforeName);
            if (!$beforeLotCategory) {
                $this->addError(ResultCode::INVALID_POSITION);
                return;
            }
        }
    }

    /**
     * @return void
     */
    protected function checkAfterName(): void
    {
        $inputDto = $this->getInputDto();
        if (isset($inputDto->afterName)) {
            if ((string)$inputDto->afterName === '') {
                $this->addError(ResultCode::INVALID_POSITION);
                return;
            }
            $afterLotCategory = $this->getLotCategoryLoader()->loadByName((string)$inputDto->afterName);
            if (!$afterLotCategory) {
                $this->addError(ResultCode::INVALID_POSITION);
                return;
            }
        }
    }
}
