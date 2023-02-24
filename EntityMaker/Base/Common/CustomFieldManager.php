<?php
/**
 * Contains all the logic of working with custom fields
 *
 * SAM-3874 Refactor SOAP service and apply unit tests
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 4, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Common;

use AuctionCustData;
use AuctionCustField;
use DateTime;
use LotItemCustData;
use LotItemCustField;
use QBaseClass;
use RuntimeException;
use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Constants;
use Sam\Core\Date\Validate\DateFormatValidator;
use Sam\Core\Platform\Constant\Base\ConstantNameResolver;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Core\Validate\Text\TextChecker;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\EntityMaker\Auction\Dto\AuctionMakerConfigDto;
use Sam\EntityMaker\Auction\Dto\AuctionMakerInputDto;
use Sam\EntityMaker\Base\Dto\ConfigDto;
use Sam\EntityMaker\Base\Dto\ConfigDtoAwareTrait;
use Sam\EntityMaker\Base\Dto\InputDto;
use Sam\EntityMaker\Base\Dto\InputDtoAwareTrait;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionCustData\AuctionCustDataWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItemCustData\LotItemCustDataWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserCustData\UserCustDataWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use UserCustData;
use UserCustField;

/**
 * Class CustomFieldManager
 * @package Sam\EntityMaker\Base
 */
abstract class CustomFieldManager extends CustomizableClass
{
    use AuctionCustDataWriteRepositoryAwareTrait;
    use BaseCustomFieldHelperAwareTrait;
    use BlockCipherProviderCreateTrait;
    use ConfigDtoAwareTrait;
    use CustomFieldHelperCreateTrait;
    use InputDtoAwareTrait;
    use LotItemCustDataWriteRepositoryAwareTrait;
    use NumberFormatterAwareTrait;
    use UserCustDataWriteRepositoryAwareTrait;

    public const CUSTOM_FIELD_CUSTOM_ERROR = 1000;
    public const CUSTOM_FIELD_DATE_ERROR = 1001;
    public const CUSTOM_FIELD_DECIMAL_ERROR = 1002;
    public const CUSTOM_FIELD_FILE_ERROR = 1003;
    public const CUSTOM_FIELD_INTEGER_ERROR = 1004;
    public const CUSTOM_FIELD_POSTAL_CODE_ERROR = 1005;
    public const CUSTOM_FIELD_REQUIRED_ERROR = 1006;
    public const CUSTOM_FIELD_SELECT_ENCODING_ERROR = 1007;
    public const CUSTOM_FIELD_SELECT_INVALID_OPTION_ERROR = 1008;
    public const CUSTOM_FIELD_TEXT_ENCODING_ERROR = 1009;
    public const CUSTOM_FIELD_TEXT_UNIQUE_ERROR = 1010;
    public const CUSTOM_FIELD_THOUSAND_SEPARATOR = 1011;
    public const CUSTOM_FIELD_INVALID_BARCODE = 1012;
    public const CUSTOM_FIELD_HIDDEN_ERROR = 1013;

    /**
     * @var string[]
     */
    protected array $addedFileNames = [];

    /**
     * @var AuctionCustData[]|LotItemCustData[]|UserCustData[]|null
     */
    protected ?array $allCustomData = null;

    /**
     * @var array customFields [id, error message]
     */
    public array $customFieldsErrors = [];

    /**
     * @var int[]
     */
    protected array $errors = [];

    /**
     * @var string[]
     */
    protected array $errorMessages = [];

    /**
     * @var bool
     */
    protected bool $isModified = false;

    /**
     * @var string[]
     */
    protected array $oldFileNames = [];

    /**
     * @param int $entityId
     * @param int $customFieldId
     * @return AuctionCustData|LotItemCustData|UserCustData
     */
    abstract protected function loadCustomDataOrCreate(int $entityId, int $customFieldId): AuctionCustData|LotItemCustData|UserCustData;

    /**
     * @return AuctionCustField[]|LotItemCustField[]|UserCustField[]
     */
    abstract protected function loadCustomFields(): array;

    /**
     * @param InputDto $inputDto
     * @param ConfigDto $configDto
     * @return static
     */
    public function construct(
        InputDto $inputDto,
        ConfigDto $configDto
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        return $this;
    }

    /**
     * Get added file names
     * @return string[]
     */
    public function getAddedFileNames(): array
    {
        return $this->addedFileNames;
    }

    /**
     * Get old file names
     * @return string[]
     */
    public function getOldFileNames(): array
    {
        return $this->oldFileNames;
    }

    /* Loading optional custom methods */

    /**
     * Save field using optional custom method if exist
     * Use $this->getConfigDto()->getMode() to specify logic for each mode
     * @param AuctionCustField|LotItemCustField|UserCustField $customField
     * @param mixed $value
     * @param AuctionCustData|LotItemCustData|UserCustData $customData
     * @return bool true, when custom save method found and executed; otherwise - false
     */
    protected function customSave(
        AuctionCustField|LotItemCustField|UserCustField $customField,
        mixed $value,
        AuctionCustData|LotItemCustData|UserCustData $customData
    ): bool {
        return false;
    }

    /**
     * Validate field using optional custom method if exist
     * Use $this->getConfigDto()->getMode() to specify logic for each mode
     * @param AuctionCustField|LotItemCustField|UserCustField $customField CustomField
     * @param string $value CustomField value
     * @return null|string
     */
    protected function customValidation(AuctionCustField|LotItemCustField|UserCustField $customField, string $value): ?string
    {
        return null;
    }

    /**
     * Get error messages
     * @return array
     */
    public function getErrorMessages(): array
    {
        $messages = [];
        foreach ($this->getErrors() as $error) {
            $messages[$error] = $this->errorMessages[$error];
        }
        return $messages;
    }

    /**
     * Get errors
     * @return int[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function initCustomFieldByName(string $name): mixed
    {
        $customField = $this->findCustomFieldByName($name);
        return $customField ? $this->initCustomField($customField) : null;
    }

    /**
     * Is lotItem modified
     * @return bool
     */
    public function isModified(): bool
    {
        return $this->isModified;
    }

    /**
     * Save custom fields
     * @return static
     */
    public function save(): static
    {
        /** @var UserMakerInputDto|LotItemMakerInputDto|AuctionMakerInputDto $inputDto */
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        foreach ($this->getAllCustomFields() as $customField) {
            $field = $this->getCustomFieldsTagName($customField->Name);
            $value = $inputDto->$field;
            if (!isset($inputDto->$field)) {
                continue;
            }

            $id = (int)$inputDto->id; // TODO: $inputDto->getPkId() or getEntityId()
            $customData = $this->loadCustomDataOrCreate($id, $customField->Id);
            $customDataOld = clone $customData;

            switch ($customField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                    $customData->Numeric = $value === '' ? null : $value;
                    break;
                case Constants\CustomField::TYPE_DECIMAL:
                    if ((string)$value === '') {
                        $customData->Numeric = null;
                    } else {
                        // SAM-11418: Avoid number formatting in API
                        $value = $configDto->mode->isSoap()
                            ? (float)$value
                            : (float)$this->getNumberFormatter()->removeFormat($value);
                        $precision = (int)$customField->Parameters;
                        $customData->assignDecimalNumeric($value, $precision);
                    }
                    break;
                case Constants\CustomField::TYPE_FILE:
                case Constants\CustomField::TYPE_FULLTEXT:
                case Constants\CustomField::TYPE_LABEL:
                case Constants\CustomField::TYPE_PASSWORD:
                case Constants\CustomField::TYPE_RICHTEXT:
                case Constants\CustomField::TYPE_SELECT:
                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_POSTALCODE:
                case Constants\CustomField::TYPE_YOUTUBELINK:
                    $customData->Text = (string)$value;
                    break;
                case Constants\CustomField::TYPE_DATE:
                    $timestamp = null;
                    if ($value) {
                        $value .= (strlen($value) === 16) ? ':00' : null;
                        $date = new DateTime($value);
                        $timestamp = $date->getTimestamp();
                    }
                    $customData->Numeric = $timestamp;
                    break;
                case Constants\CustomField::TYPE_CHECKBOX:
                    $customData->Numeric = (int)ValueResolver::new()->isTrue($value);
                    break;
            }


            $isCustomSaveExecuted = $this->customSave($customField, $value, $customData);
            if (!$isCustomSaveExecuted) {
                if (
                    $customField instanceof UserCustField
                    && $customField->Encrypted
                    && in_array($customField->Type, Constants\CustomField::$encryptedTypes, true)
                ) {
                    $customData->Text = $this->createBlockCipherProvider()->construct()->encrypt($customData->Text);
                    $customData->Encrypted = $customField->Encrypted;
                }
                $customData->Active = true;

                if ($customData instanceof AuctionCustData) {
                    $this->getAuctionCustDataWriteRepository()->saveWithModifier($customData, $configDto->editorUserId);
                } elseif ($customData instanceof LotItemCustData) {
                    $this->getLotItemCustDataWriteRepository()->saveWithModifier($customData, $configDto->editorUserId);
                } elseif ($customData instanceof UserCustData) {
                    $this->getUserCustDataWriteRepository()->saveWithModifier($customData, $configDto->editorUserId);
                } else {
                    throw new RuntimeException('Unknown custom data class ' . get_class($customData));
                }
                $this->isModified = true;
            }

            $this->additionalSavingActions($customField, $customData, $customDataOld, $configDto->editorUserId);

            if ($customField->Type === Constants\CustomField::TYPE_FILE) {
                $this->addedFileNames[$customData->Id] = explode('|', $customData->Text);
                $this->oldFileNames[$customData->Id] = $customDataOld->Text;
            }
        }
        return $this;
    }

    /**
     * Validate custom fields
     * @return bool
     */
    public function validate(): bool
    {
        /** @var UserMakerInputDto|LotItemMakerInputDto|AuctionMakerInputDto $inputDto */
        $inputDto = $this->getInputDto();
        if (!$inputDto->id) {
            $this->initCustomFieldsByDefaultData();
        }

        foreach ($this->getAllCustomFields() as $customField) {
            $field = $this->getCustomFieldsTagName($customField->Name);
            $value = (string)$inputDto->$field;

            $this->checkCustomFieldOptionalValidation($customField, $value);
            $this->checkCustomFieldRequired($customField, $value);
            $this->checkCustomFieldVisibility($customField);

            if ($value === '') {
                continue;
            }

            switch ($customField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                    $this->checkCustomFieldInteger($customField, $value);
                    break;
                case Constants\CustomField::TYPE_DECIMAL:
                    $this->checkCustomFieldDecimal($customField, $value);
                    break;
                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_FULLTEXT:
                    $this->checkCustomFieldText($customField, $value);
                    break;
                case Constants\CustomField::TYPE_SELECT:
                    $this->checkCustomFieldSelect($customField, $value);
                    break;
                case Constants\CustomField::TYPE_DATE:
                    $this->checkCustomFieldDate($customField, $value);
                    break;
                case Constants\CustomField::TYPE_FILE:
                    $this->checkCustomFieldFile($customField, $value);
                    break;
                case Constants\CustomField::TYPE_POSTALCODE:
                    $this->checkCustomFieldPostalCode($customField, $value);
                    break;
            }
        }
        $this->log();
        $isValid = empty($this->errors) && empty($this->customFieldsErrors);
        return $isValid;
    }

    protected function initCustomFieldsByDefaultData(): void
    {
        $inputDto = $this->getInputDto();
        $entityId = $inputDto->id;

        foreach ($this->getAllCustomFields() as $customField) {
            $field = $this->getCustomFieldsTagName($customField->Name);
            $fieldNotInIncomingData = !$entityId
                && !isset($inputDto->$field);
            // Assign only not empty default values to increase performance for CSV upload
            $defaultValue = $this->initCustomField($customField);

            if (
                $fieldNotInIncomingData
                && $defaultValue
            ) {
                $inputDto->$field = $defaultValue;
            }
        }
    }

    /**
     * Init custom field by default values
     * @param AuctionCustField|LotItemCustField|UserCustField $customField
     * @return mixed
     */
    public function initCustomField(QBaseClass $customField): mixed
    {
        $value = match ($customField->Type) {
            Constants\CustomField::TYPE_INTEGER,
            Constants\CustomField::TYPE_FULLTEXT,
            Constants\CustomField::TYPE_PASSWORD,
            Constants\CustomField::TYPE_TEXT => $customField->Parameters,
            Constants\CustomField::TYPE_CHECKBOX => $customField->Parameters === '1',
            default => null,
        };
        return $value;
    }

    /**
     * If calling class has own optional custom fields validation methods - run them. SAM-1570
     * @param AuctionCustField|LotItemCustField|UserCustField $customField
     * @param string $value CustomField value
     */
    protected function checkCustomFieldOptionalValidation(AuctionCustField|LotItemCustField|UserCustField $customField, string $value): void
    {
        $message = $this->customValidation($customField, $value);
        if ($message) {
            $this->addError(self::CUSTOM_FIELD_CUSTOM_ERROR, $message);
        }
    }

    /**
     * Check customField of type integer
     * @param AuctionCustField|LotItemCustField|UserCustField $customField CustomField
     * @param string $value CustomField value
     */
    protected function checkCustomFieldInteger(AuctionCustField|LotItemCustField|UserCustField $customField, string $value): void
    {
        if (!NumberValidator::new()->isInt($value)) {
            $this->addError(self::CUSTOM_FIELD_INTEGER_ERROR, null, $customField);
        }
    }

    /**
     * Check customField of type date
     * @param AuctionCustField|LotItemCustField|UserCustField $customField CustomField
     * @param string $value CustomField value
     */
    protected function checkCustomFieldDate(AuctionCustField|LotItemCustField|UserCustField $customField, string $value): void
    {
        $dateFormats = DateFormatDetector::new()->dateFormatsForMode($this->getConfigDto()->mode);
        $isValidDateTimeFormat = DateFormatValidator::new()->isValidFormatDateTime($value, $dateFormats);
        if (!$isValidDateTimeFormat) {
            $this->addError(self::CUSTOM_FIELD_DATE_ERROR, null, $customField);
        }
    }

    /**
     * Check customField of type decimal
     * @param AuctionCustField|LotItemCustField|UserCustField $customField CustomField
     * @param string $value CustomField value
     */
    protected function checkCustomFieldDecimal(AuctionCustField|LotItemCustField|UserCustField $customField, string $value): void
    {
        $configDto = $this->getConfigDto();
        // SAM-11418: Avoid number formatting in API
        if (!$configDto->mode->isSoap()) {
            $value = $this->getNumberFormatter()->removeFormat($value);
        }
        if (!NumberValidator::new()->isReal($value)) {
            $this->addError(self::CUSTOM_FIELD_DECIMAL_ERROR, null, $customField);
        }
    }

    /**
     * Check customField of type text encoding
     * @param AuctionCustField|LotItemCustField|UserCustField $customField CustomField
     * @param string $value CustomField value
     * @param int $error
     */
    protected function checkCustomFieldEncoding(AuctionCustField|LotItemCustField|UserCustField $customField, string $value, int $error): void
    {
        $configDto = $this->getConfigDto();
        if (
            $configDto->encoding
            && !TextChecker::new()->hasValidEncoding($value, $configDto->encoding)
        ) {
            $this->addError($error, null, $customField);
        }
    }

    /**
     * Check customField of type file
     * @param AuctionCustField|LotItemCustField|UserCustField $customField CustomField
     * @param string $value CustomField value
     */
    protected function checkCustomFieldFile(AuctionCustField|LotItemCustField|UserCustField $customField, string $value): void
    {
        $files = preg_split("/\s*;\s*/", $customField->Parameters);
        $extensions = explode('|', $files[0]);
        if (!in_array(pathinfo($value, PATHINFO_EXTENSION), $extensions, true)) {
            $this->addError(self::CUSTOM_FIELD_FILE_ERROR, null, $customField);
        }
    }

    /**
     * Check customField of type postalCode
     * @param AuctionCustField|LotItemCustField|UserCustField $customField CustomField
     * @param string $value CustomField value
     */
    protected function checkCustomFieldPostalCode(AuctionCustField|LotItemCustField|UserCustField $customField, string $value): void
    {
        if (!AddressChecker::new()->isPostalCode($value)) {
            $this->addError(self::CUSTOM_FIELD_POSTAL_CODE_ERROR, null, $customField);
        }
    }

    protected function checkCustomFieldVisibility(AuctionCustField|LotItemCustField|UserCustField $customField): void
    {
        /** @var UserMakerInputDto|LotItemMakerInputDto|AuctionMakerInputDto $inputDto */
        $inputDto = $this->getInputDto();
        $field = $this->getCustomFieldsTagName($customField->Name);
        $isVisible = $this->isVisible($customField);
        if (
            !$isVisible
            && isset($inputDto->{$field})
        ) {
            if (!$inputDto->id) {
                $defaultValue = $this->initCustomField($customField);
                if ($inputDto->{$field} === $defaultValue) {
                    return;
                }
            }
            $this->addError(self::CUSTOM_FIELD_HIDDEN_ERROR, null, $customField);
        }
    }

    /**
     * Check customField required
     * @param AuctionCustField|UserCustField|LotItemCustField $customField CustomField
     * @param string $value CustomField value
     */
    protected function checkCustomFieldRequired(AuctionCustField|LotItemCustField|UserCustField $customField, string $value): void
    {
        // TODO: add 'required' field to 'lot_item_cust_field' for compatibility
        if (
            $this->isRequired($customField)
            && $value === ''
            && !$this->hasCustomFieldDbValue($customField)
        ) {
            $this->addError(self::CUSTOM_FIELD_REQUIRED_ERROR, null, $customField);
        }
    }

    /**
     * @param AuctionCustField|UserCustField|LotItemCustField $customField CustomField
     * @return bool
     */
    protected function isRequired(AuctionCustField|LotItemCustField|UserCustField $customField): bool
    {
        return property_exists($customField, 'blnRequired') && $customField->Required;
    }

    /**
     * @param AuctionCustField|UserCustField|LotItemCustField $customField CustomField
     * @return bool
     */
    protected function isVisible(AuctionCustField|LotItemCustField|UserCustField $customField): bool
    {
        return true;
    }

    /**
     * Check customField of type select
     * @param AuctionCustField|LotItemCustField|UserCustField $customField CustomField
     * @param string $value CustomField value
     */
    protected function checkCustomFieldSelect(AuctionCustField|LotItemCustField|UserCustField $customField, string $value): void
    {
        $this->checkCustomFieldSelectInvalidOption($customField, $value);
        $this->checkCustomFieldEncoding($customField, $value, self::CUSTOM_FIELD_SELECT_ENCODING_ERROR);
    }

    /**
     * Check customField of type select has invalid option
     * @param AuctionCustField|LotItemCustField|UserCustField $customField CustomField
     * @param string $value CustomField value
     */
    protected function checkCustomFieldSelectInvalidOption(AuctionCustField|LotItemCustField|UserCustField $customField, string $value): void
    {
        $options = $this->getBaseCustomFieldHelper()
            ->extractDropdownOptionsFromString($customField->Parameters);
        foreach ($options as $key => $optionValue) {
            $options[$key] = trim((string)$optionValue);
        }

        if (!in_array(trim($value), $options, true)) {
            $this->addError(self::CUSTOM_FIELD_SELECT_INVALID_OPTION_ERROR, null, $customField);
        }
    }

    /**
     * Check customField of type text
     * @param AuctionCustField|LotItemCustField|UserCustField $customField CustomField
     * @param string $value CustomField value
     */
    protected function checkCustomFieldText(AuctionCustField|LotItemCustField|UserCustField $customField, string $value): void
    {
        $this->checkCustomFieldEncoding($customField, $value, self::CUSTOM_FIELD_TEXT_ENCODING_ERROR);
    }

    /**
     * @param AuctionCustField|LotItemCustField|UserCustField $customField
     * @param int $error
     * @return string
     */
    protected function addCustomFieldNameToErrorMessage(AuctionCustField|LotItemCustField|UserCustField $customField, int $error): string
    {
        $mode = $this->getConfigDto()->mode;
        if ($mode->isCsv()) {
            return $customField->Name . ' ' . lcfirst($this->errorMessages[$error]);
        }
        if ($mode->isSoap()) {
            return ucfirst($this->getCustomFieldsTagName($customField->Name)) . ' ' . lcfirst($this->errorMessages[$error]);
        }
        return $this->errorMessages[$error];
    }

    /**
     * Add error
     * @param int $error Error number
     * @param string|null $message Error message text, if @c null, the default value will be used
     * @param AuctionCustField|LotItemCustField|UserCustField|null $customField
     * @return static
     */
    protected function addError(int $error, ?string $message = null, AuctionCustField|LotItemCustField|UserCustField|null $customField = null): static
    {
        if ($customField) {
            $message = $this->addCustomFieldNameToErrorMessage($customField, $error);
            $this->customFieldsErrors[$customField->Id] = $message;
        }

        if (!in_array($error, $this->errors, true)) {
            $this->errors[] = $error;
            if ($message) {
                $this->errorMessages[$error] = $message;
            }
        }
        return $this;
    }

    /**
     * @param AuctionCustField|LotItemCustField|UserCustField $customField
     * @param AuctionCustData|LotItemCustData|UserCustData $customData
     * @param AuctionCustData|LotItemCustData|UserCustData $customDataOld
     * @param int $editorUserId
     */
    protected function additionalSavingActions(
        AuctionCustField|LotItemCustField|UserCustField $customField,
        AuctionCustData|LotItemCustData|UserCustData $customData,
        AuctionCustData|LotItemCustData|UserCustData $customDataOld,
        int $editorUserId
    ): void {
    }

    /**
     * Get all customFields from <entity>_cust_field table or from dto if assigned
     * @return AuctionCustField[]|LotItemCustField[]|UserCustField[]
     */
    protected function getAllCustomFields(): array
    {
        /** @var UserMakerConfigDto|LotItemMakerConfigDto|AuctionMakerConfigDto $configDto */
        $configDto = $this->getConfigDto();
        if ($configDto->allCustomFields === null) {
            $configDto->allCustomFields = $this->loadCustomFields();
        }
        return $configDto->allCustomFields;
    }

    /**
     * @param AuctionCustField|LotItemCustField|UserCustField $customField
     * @return bool
     */
    protected function hasCustomFieldDbValue(AuctionCustField|LotItemCustField|UserCustField $customField): bool
    {
        /** @var UserMakerInputDto|LotItemMakerInputDto|AuctionMakerInputDto $inputDto */
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (
            !$inputDto->id
            || ($configDto->mode->isWebAdmin()
                || $configDto->mode->isWebResponsive())
        ) {
            return false;
        }
        $customFieldsNumeric = [
            Constants\CustomField::TYPE_DATE,
            Constants\CustomField::TYPE_DECIMAL,
            Constants\CustomField::TYPE_CHECKBOX,
            Constants\CustomField::TYPE_INTEGER,
        ];

        $id = (int)$inputDto->id;
        $customData = $this->loadCustomDataOrCreate($id, $customField->Id);
        return (bool)(in_array($customField->Type, $customFieldsNumeric, true)
            ? $customData->Numeric
            : $customData->Text);
    }

    /**
     * Return custom fields tag name
     * @param string $name
     * @return string
     */
    protected function getCustomFieldsTagName(string $name): string
    {
        return $this->createCustomFieldHelper()->makeCustomFieldsTagName($name);
    }

    /**
     * Support logging of found errors or success
     */
    protected function log(): void
    {
        if (empty($this->errors)) {
            log_trace('Custom fields validation done');
        } else {
            // detect names of constants for error codes
            [$foundNamesToCodes, $notFoundCodes] = ConstantNameResolver::new()
                ->construct()
                ->resolveManyFromClass($this->errors, self::class);
            $foundNamesWithCodes = array_map(
                static function ($v) {
                    return "{$v[1]} ({$v[0]})";
                },
                $foundNamesToCodes
            );
            $logData = [
                'errors' => array_merge(array_values($foundNamesWithCodes), $notFoundCodes),
                'messages' => $this->customFieldsErrors,
            ];
            log_debug('Custom fields validation failed' . composeSuffix($logData));
        }
    }
}
