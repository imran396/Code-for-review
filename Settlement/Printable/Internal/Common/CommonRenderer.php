<?php
/**
 * SAM-7661: Settlement Printable & viewable version to the new layout Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-02, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Printable\Internal\Common;

use DateTime;
use Sam\Address\AddressFormatterCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\CustomField\User\Render\Web\UserCustomFieldListWebRendererAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Settings\TermsAndConditionsManagerAwareTrait;
use Sam\Settlement\Load\SettlementLocationLoaderCreateTrait;
use Sam\Settlement\Printable\Internal\Common\SaleInfo\SaleInfoRenderer;
use Sam\Settlement\Printable\Internal\Translation\SettlementTranslatorCreateTrait;
use Sam\Settlement\Render\SettlementRendererAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Settlement;

/**
 * Class CommonRenderer
 * @package Sam\Settlement\Printable
 */
class CommonRenderer extends CustomizableClass
{
    use AddressFormatterCreateTrait;
    use DateHelperAwareTrait;
    use SettlementLocationLoaderCreateTrait;
    use SettlementRendererAwareTrait;
    use SettlementTranslatorCreateTrait;
    use TermsAndConditionsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UserCustomFieldListWebRendererAwareTrait;
    use UserCustomFieldLoaderAwareTrait;
    use UserLoaderAwareTrait;

    protected Settlement $settlement;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Settlement $settlement
     * @return $this
     */
    public function construct(Settlement $settlement): static
    {
        $this->settlement = $settlement;
        return $this;
    }

    /**
     * @return string
     */
    public function renderLogoImage(): string
    {
        $output = $this->getSettlementRenderer()->renderLogoTag($this->settlement);
        return $output;
    }

    /**
     * @return string
     */
    public function renderSettlementNo(): string
    {
        return (string)$this->settlement->SettlementNo;
    }

    /**
     * @return string
     */
    public function renderDate(): string
    {
        $date = $this->settlement->SettlementDate ?: new DateTime($this->settlement->CreatedOn);
        $date = $this->getDateHelper()->convertUtcToSys($date);

        $format = $this->getTranslator()->translate('MYSETTLEMENTS_SETTLEMENT_DATE', 'mysettlements');
        return $date->format($format);
    }

    /**
     * @param int $settlementId
     * @param int $settlementAccountId
     * @return string
     */
    public function renderAddress(int $settlementId, int $settlementAccountId): string
    {
        $output = '';
        $location = $this->createSettlementLocationLoader()->load($settlementId);
        if ($location) {
            $output = $this->createAddressFormatter()
                ->format($location['country'], $location['state'], $location['city'], $location['zip'], $location['address']);
        }
        if (!$output) {
            $termsConds = $this->getTermsAndConditionsManager()->load(
                $settlementAccountId,
                Constants\TermsAndConditions::INVOICE,
                true
            );
            if (!$termsConds) {
                log_error(
                    "Available Terms and Conditions record not found for rendering multiple settlement print"
                    . composeSuffix(['acc' => $settlementAccountId, 'key' => Constants\TermsAndConditions::INVOICE])
                );
                return '';
            }
            $output = $termsConds->Content;
        }
        return $output;
    }

    /**
     * @param int $consignorUserId
     * @param bool $isUseTranslatableLabels
     * @return string
     */
    public function renderConsignorFullName(int $consignorUserId, bool $isUseTranslatableLabels): string
    {
        $tr = $this->createSettlementTranslator()->getTranslatedCommon($isUseTranslatableLabels);
        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($consignorUserId, true);
        $fullName = ee(UserPureRenderer::new()->renderFullName($userInfo));
        $output = <<<HTML
            <span class="consignor-title">{$tr->consignorInformationLbl}: </span><br />
            <span class="consignor-name">{$fullName}</span>
HTML;
        return $output;
    }

    /**
     * @param int $consignorUserId
     * @param bool $isUseTranslatableLabels
     * @return string
     */
    public function renderConsignorBillingAddress(int $consignorUserId, bool $isUseTranslatableLabels): string
    {
        $billingHtml = '';
        $user = $this->getUserLoader()->load($consignorUserId, true);
        if (!$user) {
            $logInfo = composeSuffix(['u' => $consignorUserId, 's' => $this->settlement->Id]);
            log_error("Available consignor user not found for settlement multiple print rendering" . $logInfo);
            return '';
        }
        $userBilling = $this->getUserLoader()->loadUserBillingOrCreate($consignorUserId, true);

        // Company
        if ($userBilling->CompanyName !== '') {
            $billingHtml .= '<span class="company">' . ee($userBilling->CompanyName) . '</span><br />';
        }

        // Addresses
        if (
            $userBilling->Address !== ''
            || $userBilling->Address2 !== ''
            || $userBilling->Address3 !== ''
        ) {
            $billingHtml .= '<span class="address">';

            if ($userBilling->Address !== '') {
                $billingHtml .= ee($userBilling->Address);
            }

            if (
                $userBilling->Address !== ''
                && $userBilling->Address2 !== ''
            ) {
                $billingHtml .= '<br />';
            }

            if ($userBilling->Address2 !== '') {
                $billingHtml .= ee($userBilling->Address2);
            }

            if ((
                    $userBilling->Address !== ''
                    || $userBilling->Address2 !== ''
                )
                && $userBilling->Address3 !== ''
            ) {
                $billingHtml .= '<br />';
            }

            if ($userBilling->Address3 !== '') {
                $billingHtml .= ee($userBilling->Address3);
            }

            $billingHtml .= '</span><br />';
        }

        // City
        if ($userBilling->City !== '') {
            $billingHtml .= '<span class="city">' . ee($userBilling->City) . '</span>, ';
        }

        // State
        if ($userBilling->State !== '') {
            $billingHtml .= '<span class="state">' . ee($userBilling->State) . '</span> ';
        }

        // Zip
        if ($userBilling->Zip !== '') {
            $billingHtml .= '<span class="zip">' . ee($userBilling->Zip) . '</span>';
        }

        // Phone
        if ($userBilling->Phone !== '') {
            $billingHtml .= '<br /><span class="phone">' . ee($userBilling->Phone) . '</span>';
        }

        $billingHtml .= "<br />";
        $output = $billingHtml;

        // Email
        $userEmail = ee($user->Email);
        if ($userEmail !== '') {
            $output .= '<span class="email">' . $userEmail . '</span>';
        }

        // Customer #
        if ($user->CustomerNo) {
            $tr = $this->createSettlementTranslator()->getTranslatedCommon($isUseTranslatableLabels);
            $output .= '<span class="consignor-customerno">' . $tr->userCustomerNumberLbl . ' ' . $user->CustomerNo . '</span>';
        }

        return $output;
    }

    /**
     * @return string
     */
    public function renderStatus(): string
    {
        $name = Constants\Settlement::$settlementStatusNames[$this->settlement->SettlementStatusId];
        return $name;
    }

    /**
     * @return string
     */
    public function renderNote(): string
    {
        $text = $this->settlement->Note;
        return wordwrap(nl2br($text), 80);
    }

    /**
     * @param int $consignorUserId
     * @param bool $isUseTranslatableLabels
     * @return string
     */
    public function renderConsignorCustomFields(int $consignorUserId, bool $isUseTranslatableLabels): string
    {
        $tr = $this->createSettlementTranslator()->getTranslatedCommon($isUseTranslatableLabels);
        $headerTemplate = <<<HTML
    <span class="user-cust-title">{$tr->userCustomFieldsLbl}:</span><br>%s
    <div class="divider"><hr /></div>
HTML;
        $userCustomFields = $this->getUserCustomFieldLoader()->loadInSettlements();
        $customFieldsHtml = $this->getUserCustomFieldListWebRenderer()
            ->setUserCustomFields($userCustomFields)
            ->setUserId($consignorUserId)
            ->render();
        $output = $customFieldsHtml ? sprintf($headerTemplate, $customFieldsHtml) : '';
        return $output;
    }

    public function renderSaleInfo(bool $isUseTranslatableLabels): string
    {
        if (!$this->settlement->AuctionId) {
            return '';
        }

        return SaleInfoRenderer::new()->render($this->settlement->AuctionId, $isUseTranslatableLabels);
    }
}
