<?php
/**
 * SAM-4111: Invoice and settlement fields renderers
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\User\Render;

use InvoiceUser;
use InvoiceUserBilling;
use InvoiceUserShipping;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use UserBilling;
use UserInfo;
use UserShipping;

/**
 * Class UserPureRenderer
 * @package Sam\Core\User\Render
 */
class UserPureRenderer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Full name ---

    /**
     * Return user's full name
     * @param string|null $firstName
     * @param string|null $lastName
     * @return string
     */
    public function makeFullName(?string $firstName, ?string $lastName): string
    {
        $fullName = trim($firstName . ' ' . $lastName);
        return $fullName;
    }

    /**
     * Return user's full name
     * @param UserInfo|UserBilling|UserShipping|InvoiceUser|InvoiceUserBilling|InvoiceUserShipping|null $userObject
     * @return string
     */
    public function renderFullName(UserInfo|UserBilling|UserShipping|InvoiceUser|InvoiceUserBilling|InvoiceUserShipping|null $userObject): string
    {
        $fullName = '';
        if (
            $userObject instanceof UserInfo
            || $userObject instanceof UserBilling
            || $userObject instanceof UserShipping
            || $userObject instanceof InvoiceUser
            || $userObject instanceof InvoiceUserBilling
            || $userObject instanceof InvoiceUserShipping
        ) {
            $fullName = $this->makeFullName($userObject->FirstName, $userObject->LastName);
        }
        return $fullName;
    }

    // --- E-mail ---

    /**
     * Return e-mail for accounting/billing purpose (SAM-1745)
     * @param string $userEmail
     * @param string|null $userBillingEmail
     * @param string|null $invoiceBillingEmail
     * @return string
     */
    public function makeAccountingEmail(
        string $userEmail,
        ?string $userBillingEmail = null,
        ?string $invoiceBillingEmail = null
    ): string {
        if ((string)$invoiceBillingEmail !== '') {
            $email = $invoiceBillingEmail;
        } elseif ((string)$userBillingEmail !== '') {
            $email = $userBillingEmail;
        } else {
            $email = $userEmail;
        }
        return $email;
    }

    // --- Flag ---

    /**
     * @param int $userFlag
     * @return string
     */
    public function makeFlag(int $userFlag): string
    {
        return Constants\User::FLAG_NAMES[$userFlag] ?? '';
    }

    /**
     * @param int $userFlag
     * @return string
     */
    public function makeFlagAbbr(int $userFlag): string
    {
        return Constants\User::FLAG_ABBRS[$userFlag] ?? '';
    }

    /**
     * @param int $userFlag
     * @return string
     */
    public function makeFlagCsv(int $userFlag): string
    {
        return Constants\User::FLAG_SOAP_VALUES[$userFlag] ?? '';
    }

    // --- Names from constants ---

    /**
     * @param int $contactType
     * @return string
     */
    public function makeContactType(int $contactType): string
    {
        return Constants\User::CONTACT_TYPE_NAMES[$contactType] ?? '';
    }

    /**
     * @param int $phoneType
     * @return string
     */
    public function makePhoneType(int $phoneType): string
    {
        return Constants\User::ALL_PHONE_TYPE_NAMES[$phoneType] ?? '';
    }

    /**
     * @param int $identificationType
     * @return string
     */
    public function makeIdentificationType(int $identificationType): string
    {
        return Constants\User::IDENTIFICATION_TYPE_NAMES[$identificationType] ?? '';
    }

    /**
     * @param int|null $taxApplication
     * @return string
     */
    public function makeTaxApplication(?int $taxApplication): string
    {
        return Constants\User::TAX_APPLICATION_NAMES[$taxApplication] ?? '';
    }

    /**
     * @param string|null $carrierMethod
     * @return string
     */
    public function makeCarrierMethod(?string $carrierMethod): string
    {
        return Constants\CarrierService::CARRIER_METHOD_NAMES[$carrierMethod] ?? '';
    }

    /**
     * @param string|null $bankAccountType
     * @return string
     */
    public function makeBankAccountType(?string $bankAccountType): string
    {
        return Constants\BillingBank::ACCOUNT_TYPE_NAMES[$bankAccountType] ?? '';
    }

}
