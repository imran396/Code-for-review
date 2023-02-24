<?php
/**
 * Base abstract class for validating of Entity input data
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

namespace Sam\EntityMaker\Base\Validate;

use AuctionCustField;
use DateTime;
use DateTimeZone;
use Exception;
use LotItemCustField;
use RuntimeException;
use Sam\Billing\CreditCard\Validate\CreditCardValidatorAwareTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Date\Validate\DateFormatValidator;
use Sam\Core\Email\Validate\EmailAddressChecker;
use Sam\Core\Entity\Model\User\Username\Validate\UsernamePureChecker;
use Sam\Core\Ip\Validate\NetAddressChecker;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Core\Validate\Text\TextChecker;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\EntityMaker\Base\Common\CustomFieldManager;
use Sam\EntityMaker\Base\Common\CustomFieldManagerAwareTrait;
use Sam\EntityMaker\Base\Common\DateFormatDetector;
use Sam\EntityMaker\Base\Dto\ConfigDtoAwareTrait;
use Sam\EntityMaker\Base\Dto\InputDtoAwareTrait;
use Sam\EntityMaker\Base\Validate\Internal\Location\LocationValidationIntegratorCreateTrait;
use Sam\EntitySync\Validate\EntitySyncExistenceCheckerAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Location\Validate\LocationExistenceCheckerAwareTrait;
use Sam\PhoneNumber\PhoneNumberHelperAwareTrait;
use Sam\Timezone\ApplicationTimezoneProviderAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Validate\UserExistenceCheckerAwareTrait;
use Sam\Validate\GeneralValidatorAwareTrait;
use UserCustField;

/**
 * Class Validator
 * @package Sam\EntityMaker\Base
 */
abstract class BaseMakerValidator extends CustomizableClass
{
    use ApplicationTimezoneProviderAwareTrait;
    use ConfigDtoAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CreditCardValidatorAwareTrait;
    use CurrentDateTrait;
    use CustomFieldManagerAwareTrait;
    use DateHelperAwareTrait;
    use EntityMakerImpureCheckerCreateTrait;
    use EntitySyncExistenceCheckerAwareTrait;
    use GeneralValidatorAwareTrait;
    use InputDtoAwareTrait;
    use LocationExistenceCheckerAwareTrait;
    use LocationValidationIntegratorCreateTrait;
    use NumberFormatterAwareTrait;
    use PhoneNumberHelperAwareTrait;
    use UserExistenceCheckerAwareTrait;

    /**
     * @var string[]
     */
    protected array $columnNames = [];

    /**
     * @var array customFields [id, error message]. Not all entities have custom fields
     */
    protected array $customFieldsErrors = [];

    /**
     * @var int[]
     */
    protected array $errors = [];

    /**
     * @var string[]
     */
    protected array $errorMessages = [];

    /**
     * @var array
     */
    protected array $payloads = [];

    /**
     * @var string[]
     */
    protected array $tagNames = [];

    /**
     * Get errors
     * @return int[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get error message
     * @param int $error
     * @return string|null
     */
    public function getErrorMessage(int $error): ?string
    {
        return $this->errorMessages[$error] ?? null;
    }

    /**
     * Get error messages
     * @return array
     */
    public function getErrorMessages(): array
    {
        $messages = [];
        foreach ($this->getErrors() as $error) {
            $messages[] = $this->errorMessages[$error];
        }
        return $messages;
    }

    public function getMainErrorMessages(): array
    {
        $messages = [];
        foreach ($this->getErrors() as $errorCode) {
            $messages[] = $this->errorMessages[$errorCode];
        }
        return $messages;
    }

    /**
     * Redefine error messages if necessary
     * @param string[] $messages
     */
    public function setErrorMessages(array $messages): void
    {
        foreach ($messages as $code => $message) {
            if (isset($this->errorMessages[$code])) {
                $this->errorMessages[$code] = $message;
            }
        }
    }

    public function getErrorPayload(int $error)
    {
        return $this->payloads[$error] ?? null;
    }

    /**
     * Reset errors
     */
    public function resetCustomFieldsErrors(): void
    {
        $this->customFieldsErrors = [];
        foreach ($this->errors as $key => $error) {
            if ($error >= CustomFieldManager::CUSTOM_FIELD_CUSTOM_ERROR) {
                unset($this->errors[$key]);
            }
        }
    }

    /**
     * Add error
     * @param int $error Error number
     * @param string $message Error message text, if @c null, the default value will be used
     * @param LotItemCustField|AuctionCustField|UserCustField|null $customField
     * @param mixed $payload
     * @return static
     */
    public function addError(
        int $error,
        string $message = '',
        LotItemCustField|AuctionCustField|UserCustField|null $customField = null,
        mixed $payload = null
    ): static {
        if ($customField) {
            $message = str_replace('CustomField', $customField->Name, $this->errorMessages[$error]);
            $this->customFieldsErrors[$customField->Id] = $message;
        } else {
            if ($message) {
                $this->errorMessages[$error] = $message;
            }
            if (!in_array($error, $this->errors, true)) {
                $this->errors[] = $error;
            }
        }
        if ($payload) {
            $this->payloads[$error] = $payload;
        }
        return $this;
    }

    /**
     * Add soap-tag/csv-column-name to error message
     */
    protected function addTagNamesToErrorMessages(): void
    {
        $mode = $this->getConfigDto()->mode;
        if ($mode->isCsv()) {
            $this->initColumnNames();
        }

        foreach ($this->errorMessages as $key => $errorMessage) {
            $name = $mode->isSoap() ? $this->tagNames[$key] : $this->columnNames[$key];
            $this->errorMessages[$key] = $name . ' ' . lcfirst($errorMessage);
        }
    }

    /**
     * @param int $error
     * @return bool
     */
    protected function hasError(int $error): bool
    {
        return in_array($error, $this->errors, true);
    }

    /**
     * Initialize column names for entities with CSV mode, overloading in entity validators
     */
    protected function initColumnNames(): void
    {
    }

    /**
     * Produce error message
     * @param int[] $errors
     * @return string
     */
    protected function produceErrorMessage(array $errors): string
    {
        $error = array_shift($errors);
        $message = $this->errorMessages[$error];
        return $message;
    }

    /**
     * Reset errors
     */
    protected function resetErrors(): void
    {
        $this->errors = [];
        $this->customFieldsErrors = [];
    }

    /**
     * Handle has<Field>Error(), get<Field>ErrorMessage() methods
     * @param string $methodName
     * @param array $args
     * @return bool|string
     * @throws Exception
     */
    public function __call(string $methodName, array $args = [])
    {
        if (preg_match('/get(.+)ErrorMessage/', $methodName, $matches)) {
            return $this->produceErrorMessage($this->{"get{$matches[1]}Errors"}());
        }

        if (preg_match('/has(.+)Error/', $methodName, $matches)) {
            return (bool)array_intersect($this->errors, $this->{"get{$matches[1]}Errors"}());
        }

        throw new RuntimeException("Method Validator::$methodName does not exist");
    }

    /** Validation rules */

    /**
     * Has dto field value only allowed html tags
     * @param string $field
     * @param int $error
     */
    protected function checkAllowedHtmlTags(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $hasAllowedHtmlTags = $this->getGeneralValidator()->hasAllowedHtmlTags($inputDto->$field);
        $this->addErrorIfFail($error, $hasAllowedHtmlTags);
    }

    protected function checkCountry(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isFound = AddressRenderer::new()->normalizeCountry($inputDto->$field) !== '';
        $this->addErrorIfFail($error, $isFound);
    }

    protected function checkState(string $country, string $state, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$state) {
            return;
        }

        $state = AddressRenderer::new()->normalizeState($inputDto->$country, $inputDto->$state);
        $isFound = $state !== '';
        $this->addErrorIfFail($error, $isFound);
    }

    protected function checkStates(string $country, string $states, int $error): void
    {
        foreach ((array)$this->getInputDto()->$states as $state) {
            if (!AddressRenderer::new()->normalizeState($this->getInputDto()->$country, $state)) {
                $this->addError($error, 'Tax state unknown: ' . $state);
                break;
            }
        }
    }

    /**
     * Is dto field value in array
     * @param string $field Dto field name
     * @param int $error Error number
     * @param array $array
     */
    protected function checkInArray(string $field, int $error, array $array): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        /** @noinspection TypeUnsafeArraySearchInspection */
        $inArray = in_array($inputDto->$field, $array);
        $this->addErrorIfFail($error, $inArray);
    }

    /**
     * Is dto field value in array keys
     * @param string $field Dto field name
     * @param int $error Error number
     * @param array $array
     */
    protected function checkInArrayKeys(string $field, int $error, array $array): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $inArrayKeys = array_key_exists($inputDto->$field, $array);
        $this->addErrorIfFail($error, $inArrayKeys);
    }

    /**
     * Is dto field value date
     * @param string $field Dto field name
     * @param int $error Error number
     * @param string|null $format
     */
    protected function checkDate(string $field, int $error, string $format = null): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isValidDateTimeString = $this->isValidDateTimeString($field, $format);
        $this->addErrorIfFail($error, $isValidDateTimeString);
    }

    /**
     * Is one date dto field value later than another one
     * @param string|string[] $startDate Start date or [start date, timezone]
     * @param string|string[] $endDate End date or [end date, timezone]
     * @param int $error
     */
    protected function checkDateLaterThan(string|array $startDate, string|array $endDate, int $error): void
    {
        $inputDto = $this->getInputDto();

        if (is_array($startDate)) {
            $this->isDateWithTimezoneLaterThan(
                $startDate[0],
                $startDate[1],
                $endDate[0],
                $endDate[1],
                $error
            );
            return;
        }

        // Invalid dates should not generate LaterThan error:
        if (
            !$this->isValidDateTimeString($startDate)
            || !$this->isValidDateTimeString($endDate)
        ) {
            return;
        }

        $startDateTime = new DateTime($inputDto->$startDate);
        $endDateTime = new DateTime($inputDto->$endDate);
        $isStartBeforeEnd = $startDateTime->getTimestamp() <= $endDateTime->getTimestamp();
        $this->addErrorIfFail($error, $isStartBeforeEnd);
    }

    protected function checkSyncKeyUnique(string $field, int $error, int $type, int $accountId = null): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $skipIds = $inputDto->id ? [$inputDto->id] : [];
        $isFound = $this->getEntitySyncExistenceChecker()->existByTypeAndKeyAndSyncNamespaceId(
            $type,
            $inputDto->$field,
            $inputDto->syncNamespaceId,
            $skipIds,
            false,
            $accountId
        );
        $this->addErrorIfFail($error, !$isFound);
    }

    protected function checkSyncKeyExistence(string $field, int $error, int $type, int $accountId = null): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isFound = $this->getEntitySyncExistenceChecker()->existByTypeAndKeyAndSyncNamespaceId(
            $type,
            $inputDto->$field,
            $inputDto->syncNamespaceId,
            [],
            false,
            $accountId
        );
        $this->addErrorIfFail($error, $isFound);
    }

    /**
     * Is one date dto field value later than another one
     * @param string $startDate
     * @param string $startTimezone
     * @param string $endDate
     * @param string $endTimezone
     * @param int $error
     */
    protected function isDateWithTimezoneLaterThan(
        string $startDate,
        string $startTimezone,
        string $endDate,
        string $endTimezone,
        int $error
    ): void {
        $inputDto = $this->getInputDto();
        if (
            !$inputDto->$startDate
            || !$inputDto->$startTimezone
            || !$inputDto->$endDate
            || !$inputDto->$endTimezone
        ) {
            return;
        }

        // Invalid dates should not generate LaterThan error:
        if (
            !$this->isValidDateTimeString($startDate)
            || !$this->isValidDateTimeString($endDate)
        ) {
            return;
        }

        try {
            $startDateUtc = $this->convertToUtc($inputDto->$startDate, $inputDto->$startTimezone);
            $endDateUtc = $this->convertToUtc($inputDto->$endDate, $inputDto->$endTimezone);
            $this->addErrorIfFail($error, $startDateUtc->getTimestamp() <= $endDateUtc->getTimestamp());
        } catch (Exception) {
            // Invalid timezone
            return;
        }
    }

    /**
     * Is dto field value valid email
     *
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkEmail(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isEmail = EmailAddressChecker::new()->isEmail($inputDto->$field);
        $this->addErrorIfFail($error, $isEmail);
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkExistUserId(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isUserById = $this->getUserExistenceChecker()->existById((int)$inputDto->$field);
        $this->addErrorIfFail($error, $isUserById);
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkExistLocationId(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isLocationById = $this->getLocationExistenceChecker()->existById((int)$inputDto->$field);
        $this->addErrorIfFail($error, $isLocationById);
    }

    /**
     * @param string $field
     * @param int $error
     */
    protected function checkExistLocationName(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (!$inputDto->$field) {
            return;
        }

        $isLocationByName = $this->getLocationExistenceChecker()->existByName(
            $inputDto->$field,
            $configDto->serviceAccountId
        );
        $this->addErrorIfFail($error, $isLocationByName);
    }

    /**
     * Check increments
     * @param string $field Dto field name
     * @param int[] $errors Format, range, amount errors numbers respectively
     */
    protected function checkIncrements(string $field, array $errors): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (!$inputDto->$field) {
            return;
        }

        $currentAmount = null;
        foreach ($inputDto->$field as $key => $pair) {
            $amount = (float)$pair[0];
            $increment = (float)$pair[1];

            // Data pair contains 2 values
            if (count($pair) !== 2) {
                $this->addError($errors['invalid-format']);
            }

            // First amount should be "0"
            if (
                (int)$key === 0
                && Floating::neq($amount, 0.)
            ) {
                $this->addError($errors['invalid-range']);
            }

            if ($field === 'consignorCommissions') { // Increment should not be negative number
                if (Floating::lt($increment, 0.)) {
                    $this->addError($errors['invalid-amount']);
                }
            } else {
                if (Floating::lteq($increment, 0.)) { // Increment should be positive number
                    $this->addError($errors['invalid-amount']);
                }
            }

            if ($configDto->mode->isSoap()) {
                if (!NumberValidator::new()->isReal($pair[0])) {
                    $this->addError($errors['invalid-format']);
                }
                if (!NumberValidator::new()->isReal($pair[1])) {
                    $this->addError($errors['invalid-format']);
                }
            }

            if ($amount === $currentAmount) {
                $this->addError($errors['amount-exist']);
            }
            $currentAmount = $amount;
        }
    }

    /**
     * Is dto field value integer
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkInteger(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isInt = NumberValidator::new()->isInt($inputDto->$field);
        $this->addErrorIfFail($error, $isInt);
    }

    /**
     * Is dto field value valid hostname
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkHostname(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isFullyQualifiedHostname = NetAddressChecker::new()->isFullyQualifiedHostname($inputDto->$field);
        $this->addErrorIfFail($error, $isFullyQualifiedHostname);
    }

    /**
     * Is dto field value positive integer
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkIntPositive(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        $value = $inputDto->$field;
        if (
            !isset($value)
            || $value === ''
        ) {
            return;
        }

        $isIntPositive = NumberValidator::new()->isIntPositive($value);
        $this->addErrorIfFail($error, $isIntPositive);
    }

    /**
     * Is dto field value less than max value
     * @param string $field Dto field name
     * @param int $error Error number
     * @param int $value
     */
    protected function checkLessThan(string $field, int $error, int $value): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isLessThan = (int)$inputDto->$field < $value;
        $this->addErrorIfFail($error, $isLessThan);
    }

    protected function checkLength(string $field, int $error, int $value): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isLessThan = mb_strlen($inputDto->$field) < $value;
        $this->addErrorIfFail($error, $isLessThan);
    }

    /**
     * Is dto field value between min and max values
     * @param string $field Dto field name
     * @param int $error Error number
     * @param int $min
     * @param int $max
     */
    protected function checkBetween(string $field, int $error, int $min = 0, int $max = 0): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isBetween = Floating::between($inputDto->$field, $min, $max);
        $this->addErrorIfFail($error, $isBetween);
    }

    /**
     * Is dto field value more than min value
     * @param string $field Dto field name
     * @param int $error Error number
     * @param int $value
     */
    protected function checkMin(string $field, int $error, int $value): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isHigherThan = $inputDto->$field > $value;
        $this->addErrorIfFail($error, $isHigherThan);
    }

    /**
     * Is dto field value lower than max value
     * @param string $field Dto field name
     * @param int $error Error number
     * @param int $value
     */
    protected function checkMax(string $field, int $error, int $value): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isLowerThan = $inputDto->$field < $value;
        $this->addErrorIfFail($error, $isLowerThan);
    }

    /**
     * Is dto field value numeric
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkNumeric(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isNumeric = is_numeric($inputDto->$field);
        $this->addErrorIfFail($error, $isNumeric);
    }

    /**
     * Is dto field value postal code
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkPhoneNumber(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        [$success, $message] = $this->createEntityMakerImpureChecker()->checkPhoneNumber($inputDto->$field);
        if (!$success) {
            $this->addError($error, $message);
        }
    }

    /**
     * Is dto field value postal code
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkPostalCode(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isPostalCode = AddressChecker::new()->isPostalCode($inputDto->$field);
        $this->addErrorIfFail($error, $isPostalCode);
    }

    /**
     * Is dto field value real
     * @param string $field Dto field name
     * @param int $error Error number
     * @param bool $removeFormat Use removeFormat in validation
     */
    protected function checkReal(string $field, int $error, bool $removeFormat = false): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (!$inputDto->$field) {
            return;
        }

        // SAM-11418: Avoid number formatting in API
        $value = ($removeFormat && !$configDto->mode->isSoap())
            ? $this->getNumberFormatter()->removeFormat($inputDto->$field)
            : $inputDto->$field;
        $isReal = NumberValidator::new()->isReal($value);
        $this->addErrorIfFail($error, $isReal);
    }

    /**
     * @param string $field
     * @param int $error
     * @param bool $removeFormat
     */
    protected function checkRealPositive(string $field, int $error, bool $removeFormat = false): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (!$inputDto->$field) {
            return;
        }

        // SAM-11418: Avoid number formatting in API
        $value = ($removeFormat && !$configDto->mode->isSoap())
            ? $this->getNumberFormatter()->removeFormat($inputDto->$field)
            : $inputDto->$field;
        $isRealPositive = NumberValidator::new()->isRealPositive($value);
        $this->addErrorIfFail($error, $isRealPositive);
    }

    /**
     * Is dto field required
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkRequired(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (
            $inputDto->id
            && !isset($inputDto->$field)
        ) {
            return;
        }

        if ($inputDto->$field) {
            return;
        }

        $this->addError($error);
    }

    /**
     * Are at least of one dto field required
     * @param array $fields
     * @param int $error
     */
    protected function checkAreRequired(array $fields, int $error): void
    {
        $inputDto = $this->getInputDto();
        $atLeastOneExist = false;
        foreach ($fields as $field) {
            if ($inputDto->$field) {
                $atLeastOneExist = true;
                break;
            }
        }

        $this->addErrorIfFail($error, $atLeastOneExist);
    }

    /**
     * The given field must match the field under validation
     * @param string $field Dto field name
     * @param int $error Error number
     * @param string $same Dto field name
     */
    protected function checkSame(string $field, int $error, string $same): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $this->addErrorIfFail($error, $inputDto->$field === $inputDto->$same);
    }

    /**
     * Is dto field value valid sub-domain
     * @param string $field
     * @param int $error
     */
    protected function checkSubDomain(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isSubDomain = NetAddressChecker::new()->isSubDomain($inputDto->$field);
        $this->addErrorIfFail($error, $isSubDomain);
    }

    /**
     * Is dto field value timezone
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkTimezone(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isTimezoneAvailable = $this->getApplicationTimezoneProvider()->isTimezoneAvailable($inputDto->$field);
        $this->addErrorIfFail($error, $isTimezoneAvailable);
    }

    /**
     * Is dto field value valid url
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkUrl(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isSchemeWithHostOrIp = UrlParser::new()->isSchemeWithHostOrIp($inputDto->$field);
        $this->addErrorIfFail($error, $isSchemeWithHostOrIp);
    }

    /**
     * Is dto field value valid username
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkUsername(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        if (!$inputDto->$field) {
            return;
        }

        $isValidUsername = UsernamePureChecker::new()->isValidFormat($inputDto->$field);
        $this->addErrorIfFail($error, $isValidUsername);
    }

    /**
     * Is dto field value valid url
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkEncoding(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        if (
            !$inputDto->$field
            || !$configDto->encoding
        ) {
            return;
        }

        $hasValidEncoding = TextChecker::new()->hasValidEncoding($inputDto->$field, $configDto->encoding);
        $this->addErrorIfFail($error, $hasValidEncoding);
    }

    /**
     * Is dto field value has valid thousand separator
     * @param string $field Dto field name
     * @param int $error Error number
     */
    protected function checkThousandSeparator(string $field, int $error): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $value = $inputDto->$field;
        if (
            !$value
            // SAM-11418: Avoid number formatting in API
            || $configDto->mode->isSoap()
        ) {
            return;
        }

        $numberFormatValidateResult = $this->getNumberFormatter()->validateNumberFormat($value);
        $isValidNumberWithThousandSeparator = $numberFormatValidateResult->isValidNumberWithThousandSeparator();
        $this->addErrorIfFail($error, !$isValidNumberWithThousandSeparator);
    }

    /**
     * Validate field if presented in dto
     * @param int $error
     * @param bool $condition
     */
    protected function addErrorIfFail(int $error, bool $condition): void
    {
        if (!$condition) {
            $this->addError($error);
        }
    }

    /**
     * Check customFields
     */
    protected function validateCustomFields(): void
    {
        if (!$this->customFieldManager->validate()) {
            $this->errors = array_merge($this->errors, $this->customFieldManager->getErrors());
            $this->errorMessages += $this->customFieldManager->getErrorMessages();
            $this->customFieldsErrors += $this->customFieldManager->customFieldsErrors;
        }
    }

    /**
     * Validate mutually exclusive fields
     * The field under validation must be empty or not present if the another field field is equal to any value.
     * @param string $field
     * @param array $anotherFields
     * @param int $error
     */
    protected function checkProhibits(string $field, array $anotherFields, int $error): void
    {
        $anotherFieldPresented = false;
        foreach ($anotherFields as $anotherField) {
            if ($this->getInputDto()->$anotherField) {
                $anotherFieldPresented = true;
                break;
            }
        }
        if (
            $this->getInputDto()->$field
            && $anotherFieldPresented
        ) {
            $this->addError($error);
        }
    }

    /**
     * Convert to Utc
     * @param string $date
     * @param string $timezone Timezone location
     * @return DateTime
     * @throws Exception
     */
    private function convertToUtc(string $date, string $timezone): DateTime
    {
        $dateTime = new DateTime($date, new DateTimeZone($timezone));
        $dateTime = $dateTime->setTimezone(new DateTimeZone('UTC'));
        return $dateTime;
    }

    /**
     * Helper function to validate date strings:
     *
     * @param string $field dto field name
     * @param string|null $format
     * @return bool
     */
    private function isValidDateTimeString(string $field, ?string $format = null): bool
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $dateFormats = $format ?: DateFormatDetector::new()
            ->dateFormatsForMode($configDto->mode);
        return DateFormatValidator::new()->isValidFormatDateTime((string)$inputDto->$field, $dateFormats);
    }
}
