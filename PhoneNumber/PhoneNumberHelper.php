<?php
/**
 * Helper class for phone number related functionality
 *
 * Phone number parts array $phoneParts consists of the next elements:
 * 0: string $countryCode        country code
 * 1: string $phoneNumber        phone number (area code/regional code + subscriber number + extension (optional))
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 11, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\PhoneNumber;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;

/**
 * Class Helper
 * @package Sam\PhoneNumber
 */
class PhoneNumberHelper extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use TranslatorAwareTrait;

    /**
     * @var string[]
     */
    protected array $errorMessages = [];
    /**
     * @var string[]
     */
    protected array $langs = [];

    /**
     * Class instantiation method
     * @return static or customized class extending from it
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Init instance with defaults, inject dependencies
     * @return static
     */
    public function initInstance(): static
    {
        $this->errorMessages = [];
        $this->initTranslations();
        return $this;
    }

    /**
     * Initialize translations
     */
    protected function initTranslations(): void
    {
        $this->langs['USER_ERR_PHONE_NUMBER_INVALID_TRY_FORMAT'] = $this->getTranslator()->translate('USER_ERR_PHONE_NUMBER_INVALID_TRY_FORMAT', 'user');
    }

    /**
     * Check if phone number is valid
     * Phone number extension isn't considered in validation
     * @param string $phoneNumber
     * @return bool
     */
    public function isValid(string $phoneNumber): bool
    {
        $this->clearErrors();
        if ($this->cfg()->get('core->user->phoneNumberFormat') === 'simple') {
            return true;
        }

        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        try {
            /** @var PhoneNumber $phoneNumberManager */
            $phoneNumberManager = $phoneNumberUtil->parse($phoneNumber, null, null, true);
            $isValid = $phoneNumberUtil->isValidNumber($phoneNumberManager);
            if (!$isValid) {
                $message = sprintf(
                    $this->langs['USER_ERR_PHONE_NUMBER_INVALID_TRY_FORMAT'],
                    $phoneNumber,
                    $this->getStructuredFormatTemplate($phoneNumber)
                );
                $this->addError($message);
            }
        } catch (NumberParseException $e) {
            $message = sprintf(
                    $this->langs['USER_ERR_PHONE_NUMBER_INVALID_TRY_FORMAT'] . ' ',
                    $phoneNumber,
                    $this->getStructuredFormatTemplate($phoneNumber)
                ) .
                $e->getMessage();
            $this->addError($message);
            log_info(
                'Exception raised on phone number "' . $phoneNumber . '" ' .
                composeLogData(['validation' => $message])
            );
            $isValid = false;
        }
        return $isValid;
    }

    /**
     * Split structured phone number to parts
     * @param string $phoneNumber structured phone number
     * @return array:
     *                               0: string $countryCode        country code
     *                               1: string $phoneNumber        phone number (area code + subscriber number + extension)
     */
    public function getParts(string $phoneNumber): array
    {
        try {
            $phoneNumberUtil = PhoneNumberUtil::getInstance();
            /** @var PhoneNumber $phoneNumberManager */
            $phoneNumberManager = $phoneNumberUtil->parse($phoneNumber, null, null, true);
            $countryCode = $phoneNumberManager->getCountryCode();
            $phoneNumber = $phoneNumberUtil->format($phoneNumberManager, PhoneNumberFormat::INTERNATIONAL);
            $phoneNumber = substr($phoneNumber, strlen((string)$countryCode) + 2);
        } catch (NumberParseException) {
            // Try to get country code from invalid phone number, admin can save invalid phone numbers
            $countryCode = $this->extractCountryCode($phoneNumber);
            if ($countryCode) {
                $phoneNumber = substr($phoneNumber, strlen($countryCode) + 2);
            }
        }
        $phoneParts = [$countryCode, $phoneNumber];
        return $phoneParts;
    }

    /**
     * Extract country calling code from passed phone number
     * @param string $phoneNumber
     * @return string|null null - when all tries to parse country code failed.
     */
    public function extractCountryCode(string $phoneNumber): ?string
    {
        $countryCode = null;
        $phoneNumber = trim($phoneNumber);
        // 1) Try to parse number and extract country code using libphonenumber library
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        try {
            /** @var PhoneNumber $phoneNumberManager */
            $phoneNumberManager = $phoneNumberUtil->parse($phoneNumber, null, null, true);
            $countryCode = $phoneNumberManager->getCountryCode();
            return Cast::toString($countryCode);
        } catch (NumberParseException) {
        }
        // 2) Try to extract country code manually
        if (preg_match('/^\+(\d{1,3})/', $phoneNumber, $matches)
            && $phoneNumberUtil->getRegionCodeForCountryCode(Cast::toInt($matches[1]))
        ) {
            $countryCode = $matches[1];
        }
        return $countryCode;
    }

    /**
     * Compose structured phone number from parts
     * @param array $phoneParts
     * @return string
     */
    public function composeFromParts(array $phoneParts): string
    {
        $phoneCode = (string)$phoneParts[0];
        $phoneNumbers = $phoneParts[1];
        if (
            $phoneCode !== ''
            && $phoneCode[0] !== '+'
        ) {
            $phoneCode = '+' . $phoneCode;
        }
        $txtStructuredPhoneNumber = trim($phoneCode . " " . $phoneNumbers);
        return $txtStructuredPhoneNumber;
    }

    /**
     * Return phone number in international format, if it possible, either return passed phone number
     * Use getLastErrors()
     * @param string $phoneNumber
     * @return string
     */
    public function formatToInternational(string $phoneNumber): string
    {
        $this->clearErrors();
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        try {
            /** @var PhoneNumber $phoneNumberManager */
            $phoneNumberManager = $phoneNumberUtil->parse($phoneNumber, null, null, true);
            return $phoneNumberUtil->format($phoneNumberManager, PhoneNumberFormat::INTERNATIONAL);
        } catch (NumberParseException $e) {
            $this->errorMessages[] = $e->getMessage();
            log_info(
                'Exception raised on phone number "' . $phoneNumber . '" ' .
                'formatting to international format: ' . $e->getMessage()
            );
            return $phoneNumber;
        }
    }

    /**
     * Return template string for phone number in structured format
     * @param string $phoneNumber We will try to extract country code from it to find international format
     * @return string
     */
    public function getStructuredFormatTemplate(string $phoneNumber): string
    {
        $template = '+{country code} {phone number}';
        if ($phoneNumber !== '') {
            $phoneNumberUtil = PhoneNumberUtil::getInstance();
            $countryCode = $this->extractCountryCode($phoneNumber);
            $regionCode = $phoneNumberUtil->getRegionCodeForCountryCode(Cast::toInt($countryCode));
            if ($regionCode !== 'ZZ') {
                $exampleNumber = (string)$phoneNumberUtil->getExampleNumber($regionCode);
                $phoneParts = $this->getParts($exampleNumber);
                $phoneParts[1] = preg_replace('/\d/', 'x', $phoneParts[1]);
                $template = $this->composeFromParts($phoneParts);
            }
        }
        return $template;
    }

    /**
     * Try to update country code in phone number by code found by region
     * @param string $phoneNumber
     * @param string $regionCode
     * @return string
     */
    public function updateCountryCode(string $phoneNumber, string $regionCode = Constants\Country::C_USA): string
    {
        $phoneMeta = PhoneNumberUtil::getInstance()->getMetadataForRegion($regionCode);
        if ($phoneMeta) {
            $phoneParts = $this->getParts($phoneNumber);
            $phoneParts[0] = $phoneMeta->getCountryCode();
            $phoneNumber = $this->composeFromParts($phoneParts);
        }
        return $phoneNumber;
    }

    /**
     * Check if errors happened
     * @return bool
     */
    public function isError(): bool
    {
        return count($this->errorMessages) > 0;
    }

    /**
     * Return errors happened on the last action
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errorMessages;
    }

    /**
     * Return errors composed in single message
     * @param string $separator
     * @return string
     */
    public function getErrorMessage(string $separator = "\n"): string
    {
        return implode($separator, $this->errorMessages);
    }

    /**
     * Add error message
     * @param string $message
     */
    protected function addError(string $message): void
    {
        $this->errorMessages[] = $message;
    }

    /**
     * Clear errors array
     */
    protected function clearErrors(): void
    {
        $this->errorMessages = [];
    }
}
