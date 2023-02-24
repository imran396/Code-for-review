<?php
/**
 * Help methods for Credit Card validation
 *
 * SAM-4088: CreditCardLoader and CreditCardExistenceChecker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis, Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 23, 2018
 */

namespace Sam\Billing\CreditCard\Validate;

use DateTime;
use Sam\Billing\CreditCard\Build\CcNumberEncrypterAwareTrait;
use Sam\Core\Constants;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

/**
 * Class CreditCardValidator
 */
class CreditCardValidator extends CustomizableClass
{
    use CcNumberEncrypterAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * validate Expired Date Format, ex. 01-2001
     * @param string $date
     * @return bool
     */
    public function validateFormatOfExpiredDate(string $date): bool
    {
        $isValid = strlen($date) === 7
            && preg_match('/^(0[1-9]|1[0-2])[-](19|20|21)\d{2}$/', $date);
        return $isValid;
    }

    /**
     * Validate Expired Date
     * @param DateTime $date
     * @return bool
     */
    public function validateExpiredDate(DateTime $date): bool
    {
        $expiredDate = new DateTime($date->format('Y-m-t 23:59:59'));
        $currentDateUtc = $this->getCurrentDateUtc();
        $isValid = $expiredDate->getTimestamp() >= $currentDateUtc->getTimestamp();
        return $isValid;
    }

    /**
     * Validate Expired Date Formatted
     * @param string $dateFormatted
     * @return bool
     */
    public function validateExpiredDateFormatted(string $dateFormatted): bool
    {
        if (!$this->validateFormatOfExpiredDate($dateFormatted)) {
            return false;
        }
        $date = new DateTime("01-{$dateFormatted}");
        return $this->validateExpiredDate($date);
    }

    /**
     * Validate Expired Date Formatted
     * @param string $year
     * @param string $month
     * @return bool
     */
    public function validateExpiredDateByYearAndMonth(string $year, string $month): bool
    {
        $date = new DateTime("{$year}-{$month}-01");
        return $this->validateExpiredDate($date);
    }

    /**
     * @param string $ccNumber
     * @param string|null $ccType
     * @return bool
     */
    public function validateNumber(string $ccNumber, ?string $ccType = null): bool
    {
        if ($this->cfg()->get('core->billing->skipCcValidation')) {
            return true;
        }
        $ccType = strtolower($ccType ?? '');
        $ccTypes = [];
        switch ($ccType) {
            case 'amex':
            case 'american express':
                $ccTypes[] = 'AMEX';
                break;
            case 'discover':
                $ccTypes[] = 'DISCOVER';
                break;
            case 'visa':
                $ccTypes[] = 'VISA';
                break;
            case 'master card':
            case 'mastercard':
            case 'diners club us':
                $ccTypes[] = 'MASTERCARD';
                break;
            case 'diners':
            case 'diners club':
                $ccTypes[] = 'DINERS';
                break;
            case 'japan credit bureau':
            case 'jcb':
                $ccTypes[] = 'JCB';
                break;
            case 'unionpay':
                $ccTypes[] = 'CHINA_UNIONPAY';
                break;
            case 'laser':
                $ccTypes[] = 'LASER';
                break;
            case 'maestro':
                $ccTypes[] = 'MAESTRO';
                break;
            default:
                $ccTypes = [
                    'AMEX',
                    'CHINA_UNIONPAY',
                    'DINERS',
                    'DISCOVER',
                    'INSTAPAYMENT',
                    'JCB',
                    'LASER',
                    'MAESTRO',
                    'MASTERCARD',
                    'MIR',
                    'UATP',
                    'VISA',
                ];
                break;
        }
        $validationErrors = Validation::createValidator()->validate($ccNumber, new Assert\CardScheme(['schemes' => $ccTypes]));
        $isValid = count($validationErrors) === 0;
        return $isValid;
    }

    /**
     * @param string $ccNumberHash
     * @param string $ccNumber
     * @return bool
     */
    public function validateHash(string $ccNumberHash, string $ccNumber): bool
    {
        $ccNumberSha1 = base64_decode($ccNumberHash, true);
        if ($ccNumberSha1 === false) { //Test if valid base64 encoded
            return false;
        }

        if (strlen($ccNumberSha1) !== 20) {
            return false;
        }

        if (
            $ccNumber
            && $this->isFullCcNumber($ccNumber) // full cc number
            && !$this->getCcNumberEncrypter()->verifyHash($ccNumber, $ccNumberHash)
        ) {
            return false;
        }
        return true;
    }

    /**
     * Detect credit card type by number
     * @param string $ccNum
     * @return int|null return null if type is not detected
     */
    public function detectType(string $ccNum): ?int
    {
        /**
         * mastercard: Must have a prefix of 51 to 55, and must be 16 digits in length.
         * Visa: Must have a prefix of 4, and must be either 13 or 16 digits in length.
         * American Express: Must have a prefix of 34 or 37, and must be 15 digits in length.
         * Diners Club: Must have a prefix of 300 to 305, 36, or 38, and must be 14 digits in length.
         * Discover: Must have a prefix of 6011, and must be 16 digits in length.
         * JCB: Must have a prefix of 3, 1800, or 2131, and must be either 15 or 16 digits in length.
         */
        $number = preg_replace('/\D/', '', $ccNum);
        if (preg_match('/^3[47]\d{13}$/', $number)) {
            return Constants\CreditCard::T_AMEX;
        }

        if (preg_match('/^3(?:0[0-5]|[68]\d)\d{11}$/', $number)) {
            return Constants\CreditCard::T_DINERS_CLUB;
        }

        if (preg_match('/^6(?:011|5\d\d)\d{12}$/', $number)) {
            return Constants\CreditCard::T_DISCOVER;
        }

        if (preg_match('/^(?:2131|1800|35\d{3})\d{11}$/', $number)) {
            return Constants\CreditCard::T_JCB;
        }

        if (preg_match('/^5[1-5]\d{14}$/', $number)) {
            return Constants\CreditCard::T_MASTERCARD;
        }

        if (preg_match('/^4\d{12}(?:\d{3})?$/', $number)) {
            return Constants\CreditCard::T_VISA;
        }

        return null;
    }

    /**
     * Check credit card number is of the right sort of length.
     * @param string $ccNumber
     * @return bool
     */
    public function isFullCcNumber(string $ccNumber): bool
    {
        return strlen($ccNumber) > 4;
    }
}
