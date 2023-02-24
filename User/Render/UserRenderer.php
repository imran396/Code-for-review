<?php
/**
 * Helping methods for user fields rendering
 *
 * SAM-4140: User Renderer helper
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 9, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Render;

use InvoiceUserBilling;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Email\Validate\EmailAddressChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use User;
use UserBilling;

/**
 * Class UserRenderer
 * @package Sam\User\Render
 */
class UserRenderer extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use BidderNumPaddingAwareTrait;
    use ConfigRepositoryAwareTrait;
    use TranslatorAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if username looks like e-mail and mask it then.
     * @param string $username
     * @return string
     */
    public function maskUsernameIfAlikeEmail(string $username): string
    {
        if (!EmailAddressChecker::new()->isEmail($username)) {
            return $username;
        }

        [$search, $replace] = $this->cfg()->get('core->responsive->user->username->maskRegExpIfEmail')->toArray();
        $username = preg_replace($search, $replace, $username);
        return $username;
    }

    /**
     * Return bidder info, which is used as option in bidder selection dropdown
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $username
     * @param string|null $email
     * @param string|null $customerNo
     * @param string|null $bidderNum
     * @return string
     */
    public function makeNameLine(
        ?string $firstName,
        ?string $lastName,
        ?string $username,
        ?string $email,
        ?string $customerNo,
        ?string $bidderNum = null
    ): string {
        $firstName = (string)$firstName;
        $lastName = (string)$lastName;
        $username = (string)$username;
        $email = (string)$email;
        $customerNo = trim((string)$customerNo);
        $bidderNum = (string)$bidderNum;

        $result = $lastName;
        if ($firstName !== '') {
            $result .= ' ' . $firstName;
        }
        if ($result !== '') {
            $result .= ', ';
        }
        $result .= $username;
        $bidderCustomerNum = '';
        if ($bidderNum !== '') {
            $bidderNum = $this->getBidderNumberPadding()->clear($bidderNum);
            $bidderCustomerNum = '#' . $bidderNum;
        }
        if ($customerNo !== '') {
            if ($bidderCustomerNum !== '') {
                $bidderCustomerNum .= ', ';
            }
            $bidderCustomerNum .= 'Cust#' . $customerNo;
        }
        if ($bidderCustomerNum !== '') {
            $bidderCustomerNum = ' (' . $bidderCustomerNum . ')';
        }
        $result .= $bidderCustomerNum;
        if ($email !== '') {
            $result .= ' - ' . $email;
        }
        return $result;
    }

    /**
     * Return e-mail for accounting/billing purpose (SAM-1745)
     * @param User $user
     * @param UserBilling|null $userBilling
     * @param InvoiceUserBilling|null $invoiceBilling
     * @return string
     */
    public function renderAccountingEmail(
        User $user,
        UserBilling $userBilling = null,
        InvoiceUserBilling $invoiceBilling = null
    ): string {
        $invoiceBillingEmail = $invoiceBilling->Email ?? null;
        if (!$invoiceBillingEmail
            && !$userBilling
        ) {
            $userBilling = $this->getUserLoader()->loadUserBilling($user->Id);
        }
        $userBillingEmail = $userBilling->Email ?? null;
        $email = UserPureRenderer::new()->makeAccountingEmail($user->Email, $userBillingEmail, $invoiceBillingEmail);
        return $email;
    }

    /**
     * @param int $contactType
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    public function makeContactTypeTranslated(
        int $contactType,
        ?int $accountId = null,
        ?int $languageId = null
    ): string {
        $langContactTypes = [
            Constants\User::CT_WORK => 'GENERAL_WORK',
            Constants\User::CT_HOME => 'GENERAL_HOME',
        ];
        $output = isset($langContactTypes[$contactType])
            ? $this->getTranslator()->translate(
                $langContactTypes[$contactType],
                'general',
                $accountId,
                $languageId
            )
            : '';
        return $output;
    }

    /**
     * @param int $identificationType
     * @return string
     */
    public function makeIdentificationTypeTranslatedForAdmin(int $identificationType): string
    {
        $key = UserPureRenderer::new()->makeIdentificationType($identificationType);
        if (!$key) {
            return '';
        }
        return $this->getAdminTranslator()->trans('user.info.identification_type.' . strtolower($key) . '.label');
    }

    /**
     * @param int $identificationType
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    public function makeIdentificationTypeTranslated(
        int $identificationType,
        ?int $accountId = null,
        ?int $languageId = null
    ): string {
        $langIdentificationTypes = [
            Constants\User::IDT_DRIVERSLICENSE => 'USER_IDENTIFICATIONTYPE_DRIVERSLICENSE',
            Constants\User::IDT_PASSPORT => 'USER_IDENTIFICATIONTYPE_PASSPORT',
            Constants\User::IDT_SSN => 'USER_IDENTIFICATIONTYPE_SSN',
            Constants\User::IDT_VAT => 'USER_IDENTIFICATIONTYPE_VAT',
            Constants\User::IDT_OTHER => 'USER_IDENTIFICATIONTYPE_OTHER',
        ];
        $output = isset($langIdentificationTypes[$identificationType])
            ? $this->getTranslator()->translate(
                $langIdentificationTypes[$identificationType],
                'user',
                $accountId,
                $languageId
            )
            : '';
        return $output;
    }

    /**
     * @param int $phoneType
     * @return string
     */
    public function makePhoneTypeTranslatedForAdmin(int $phoneType): string
    {
        $key = UserPureRenderer::new()->makePhoneType($phoneType);
        if (!$key) {
            return '';
        }
        return $this->getAdminTranslator()->trans('user.info.phone_type.' . strtolower($key) . '.label');
    }

    /**
     * @param int $phoneType
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    public function makePhoneTypeTranslated(
        int $phoneType,
        ?int $accountId = null,
        ?int $languageId = null
    ): string {
        $langPhoneTypes = [
            Constants\User::PT_WORK => 'GENERAL_WORK',
            Constants\User::PT_HOME => 'GENERAL_HOME',
            Constants\User::PT_MOBILE => 'GENERAL_MOBILE',
        ];
        $output = isset($langPhoneTypes[$phoneType])
            ? $this->getTranslator()->translate(
                $langPhoneTypes[$phoneType],
                'general',
                $accountId,
                $languageId
            )
            : '';
        return $output;
    }
}
