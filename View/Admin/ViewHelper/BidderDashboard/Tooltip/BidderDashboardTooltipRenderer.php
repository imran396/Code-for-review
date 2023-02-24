<?php
/**
 * SAM-10226: Refactor bidder dashboard tooltip for v3-7
 * SAM-4881: Refactor user dashboard tooltip module
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           15 Apr, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\BidderDashboard\Tooltip;

use DateTime;
use Sam\Core\Constants\Admin\BidderDashboardTooltipConstants;
use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\Config\User\AnySingleUserUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\CustomField\User\Load\UserCustomDataLoaderAwareTrait;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Account\Statistic\Load\UserAccountStatisticLoaderCreateTrait;
use Sam\User\Account\Statistic\Save\UserAccountStatisticProducerAwareTrait;

/**
 * Class DashboardTooltip
 * @package Sam\User
 */
class BidderDashboardTooltipRenderer extends CustomizableClass
{
    use ApplicationAccessCheckerCreateTrait;
    use BackUrlParserAwareTrait;
    use BlockCipherProviderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use DateHelperAwareTrait;
    use EditorUserAwareTrait;
    use NumberFormatterAwareTrait;
    use ParamFetcherForGetAwareTrait;
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;
    use UrlBuilderAwareTrait;
    use UrlParserAwareTrait;
    use UserAccountStatisticLoaderCreateTrait;
    use UserAccountStatisticProducerAwareTrait;
    use UserAwareTrait;
    use UserCustomDataLoaderAwareTrait;
    use UserCustomFieldLoaderAwareTrait;

    protected array $userAccountStats = [];
    protected array $userAccountStatsTotal = [];
    protected array $userAccountStatsCurrency = [];
    protected string $dashboardUrl = '';
    protected bool $isShareStats = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function render(int $userId): string
    {
        $this->isShareStats = $this->cfg()->get('core->portal->enabled')
            && $this->getSettingsManager()->getForMain(Constants\Setting::SHARE_USER_STATS);

        $this->setUserId($userId);
        $this->initUserAccountData();

        $userBilling = $this->getUserBillingOrCreate();

        $allUserCustomFields = $this->getUserCustomFieldLoader()->loadAll(true);
        $userCustomFieldsForBilling = [];
        foreach ($allUserCustomFields as $userCustomField) {
            if ($userCustomField->Panel === Constants\UserCustomField::PANEL_BILLING) {
                $userCustomFieldsForBilling[$userCustomField->Id] = $userCustomField;
            }
        }
        $userCustomFields = $this->getUserCustomDataLoader()->loadForUser($this->getUserId());
        $userCustomFieldsAll = [];
        $userCustomFieldValues = [];
        foreach ($userCustomFields as $userCustomField) {
            $userCustomFieldsAll[$userCustomField->UserCustFieldId] = $userCustomField;
        }

        foreach ($userCustomFieldsForBilling as $userCustomFieldForBilling) {
            if (!$userCustomFieldForBilling->Active) {
                continue;
            }

            $key = $userCustomFieldForBilling->Name;
            if ($userCustomFieldForBilling->Type === Constants\CustomField::TYPE_LABEL) {
                $userCustomFieldValues[$key] = $userCustomFieldForBilling->Parameters;
            } elseif ($userCustomFieldForBilling->Type === Constants\CustomField::TYPE_DATE) {
                if (isset($userCustomFieldsAll[$userCustomFieldForBilling->Id])) {
                    $userCustomFieldValues[$key] = date(
                        $userCustomFieldForBilling->Parameters,
                        $userCustomFieldsAll[$userCustomFieldForBilling->Id]->Numeric
                    );
                }
            } elseif (!empty($userCustomFieldsAll[$userCustomFieldForBilling->Id])) {
                if ($userCustomFieldForBilling->Type === Constants\CustomField::TYPE_CHECKBOX) {
                    if ($userCustomFieldsAll[$userCustomFieldForBilling->Id]->Numeric) {
                        $userCustomFieldValues[$key] = 'Yes';
                    } else {
                        $userCustomFieldValues[$key] = 'No';
                    }
                } elseif ($userCustomFieldsAll[$userCustomFieldForBilling->Id]->Text) {
                    $userCustomFieldValues[$key] = $userCustomFieldsAll[$userCustomFieldForBilling->Id]->Text;
                } elseif ($userCustomFieldForBilling->Type === Constants\CustomField::TYPE_DECIMAL) {
                    $precision = (int)$userCustomFieldForBilling->Parameters;
                    $value = $userCustomFieldsAll[$userCustomFieldForBilling->Id]->calcDecimalValue($precision);
                    $userCustomFieldValues[$key] = $value;
                } else {
                    $userCustomFieldValues[$key] = $userCustomFieldsAll[$userCustomFieldForBilling->Id]->Numeric;
                }
            }
        }

        $helpSection = 'admin_user_dashboard';
        $helpSectionBill = 'admin_user_billing';

        $totalBid = '';
        foreach ($this->getAmountPerCurrency() as $currency => $currencyAmounts) {
            $totalBid .= '<div class="currency-total"><span class="currency">' . $currency . '</span>'
                . '<span class="total">'
                . $this->getNumberFormatter()->formatMoney($currencyAmounts['lots_bid_on_amt'])
                . '</span>';
            if ($this->shareStats()) {
                $totalBid .= '(<span class="currency">' . $currency . '</span>'
                    . '<span class="total">'
                    . $this->getNumberFormatter()->formatMoney($currencyAmounts['lots_bid_on_amt_total'])
                    . '</span>)';
            }
            $totalBid .= '</div>';
        }

        $totalWon = '';
        foreach ($this->getAmountPerCurrency() as $currency => $currencyAmounts) {
            $totalWon .= '<div class="currency-total"><span class="currency">' . $currency . '</span>'
                . '<span class="total">'
                . $this->getNumberFormatter()->formatMoney($currencyAmounts['lots_won_amt'])
                . '</span>';
            if ($this->shareStats()) {
                $totalWon .= '(<span class="currency">' . $currency . '</span>'
                    . '<span class="total">'
                    . $this->getNumberFormatter()->formatMoney($currencyAmounts['lots_won_amt_total'])
                    . '</span>)';
            }
            $totalWon .= '</div>';
        }

        $consignorStats = '';
        if ($this->getUserConsignorPrivilegeChecker()->isConsignor()) {
            $totalConsigned = '';
            foreach ($this->getAmountPerCurrency() as $currency => $currencyAmounts) {
                $totalConsigned .= '<div class="currency-total"><span class="currency">' . $currency . '</span>'
                    . '<span class="total">'
                    . $this->getNumberFormatter()->formatMoney($currencyAmounts['lots_consigned_sold_amt'])
                    . '</span>';
                if ($this->shareStats()) {
                    $totalConsigned .= '(<span class="currency">' . $currency . '</span>'
                        . '<span class="total">'
                        . $this->getNumberFormatter()->formatMoney($currencyAmounts['lots_consigned_sold_amt_total'])
                        . '</span>)';
                }
                $totalConsigned .= '</div>';
            }

            $consignorStats .= '
            <h3>Selling</h3>
            <div class="label-value lots-consigned">
                <div class="label">' . _hl('Lots consigned:', $helpSection, 'lots_consigned', false) . '</div>
                <div class="value">' . $this->renderNumberConsignedItems() . '</div>
            </div>
            <div class="label-value consigned-lots-sold">
                <div class="label">' . _hl('Consigned lots sold:', $helpSection, 'consigned_lots_sold', false) . '</div>
                <div class="value">' . $this->renderNumberSoldItems() . '</div>
            </div>
            <div class="label-value account">
                <div class="label">' . _hl(
                    'Total consigned lots sold:',
                    $helpSection,
                    'total_consigned_lots_sold',
                    false
                ) . '</div>
                <div class="value">' . $totalConsigned . '</div>
            </div>';
        }

        $cidBtnUpdate = BidderDashboardTooltipConstants::CID_BTN_UPDATE;
        $output = '

<div id="plnDb8_ctl" class="userdashboardpanelforbiiders-ctl" style="display:inline;">
    <div id="plnDb8" >

    <div class="user-form">
            <fieldset>
                <legend>Dashboard</legend>
                <div class="form user-dashboard">
                    <h3>General</h3>
                    <div class="label-value last-logged">
                        <div class="label">' . _hl('Last logged in:', $helpSection, 'last_logged', false) . '</div>
                        <div class="value">' . $this->renderLastDateLoggedIn() . '</div>
                    </div>
                    <div class="label-value last-registered">
                        <div class="label">'
            . _hl('Last date registered:', $helpSection, 'last_date_registered', false)
            . '</div>
                        <div class="value">' . $this->renderLastDateRegistered() . '</div>
                    </div>
                    <div class="label-value registered-auctions">
                        <div class="label">' . _hl('Registered auctions:', $helpSection, 'registered_auction', false) . '</div>
                        <div class="value">' . $this->renderNumberRegisteredAuction() . '</div>
                    </div>
                    <div class="label-value participated-auctions">
                        <div class="label">' . _hl(
                'Participated auctions:',
                $helpSection,
                'participated_auctions',
                false
            ) . '</div>
                        <div class="value">' . $this->renderNumberParticipatedAuction() . '</div>
                    </div>
                    <div class="label-value auctions-participated-percentage">
                        <div class="label">' . _hl(
                'Auctions participated:',
                $helpSection,
                'auctions_participated',
                false
            ) . '</div>
                        <div class="value">' . $this->renderPercentParticipatedAuction() . '</div>
                    </div>
                    <div class="label-value stats-last-updated">
                        <div class="label">' . _hl(
                'Stats last updated:',
                $helpSection,
                'stats_last_updated',
                false
            ) . '</div>
                        <div class="value">' . $this->renderStatsLastUpdated() . '</div>
                    </div>
                    <h3>Watchlist</h3>
                    <div class="label-value lots-watchlist">
                        <div class="label">' . _hl('Lots in watchlist:', $helpSection, 'lots_in_watchlist', false) . '</div>
                        <div class="value">' . $this->renderNumberWatchlistItems() . '</div>
                    </div>
                    <div class="label-value watched-lots-bidon">
                        <div class="label">' . _hl('Watched items bid on:', $helpSection, 'watched_items_bid', false) . '</div>
                        <div class="value">' . $this->renderPercentWatchlistBidOn() . '</div>
                    </div>

                    <div class="label-value watched-lots-won">
                        <div class="label">' . _hl('Watched items won:', $helpSection, 'watched_items_won', false) . '</div>
                        <div class="value">' . $this->renderPercentWatchlistWon() . '</div>
                    </div>
                    <h3>Bidding</h3>
                    <div class="label-value last-bid">
                        <div class="label">' . _hl('Last date bid:', $helpSection, 'last_date_bid', false) . '</div>
                        <div class="value">' . $this->renderLastDateBidOn() . '</div>
                    </div>
                    <div class="label-value lots-bid-on">
                        <div class="label">' . _hl('Lots bid on:', $helpSection, 'lots_bid_on', false) . '</div>
                        <div class="value">' . $this->renderNumberBidOnItems() . '</div>
                    </div>
                    <div class="label-value total-bid-on">
                        <div class="label">' . _hl('Total bid on:', $helpSection, 'total_bid_on', false) . '</div>
                        <div class="value">' . $totalBid . '</div>
                    </div>
                    <h3>Buying</h3>
                    <div class="label-value last-won">
                        <div class="label">' . _hl('Last date won:', $helpSection, 'last_date_won', false) . '</div>
                        <div class="value">' . $this->renderLastDateWonOn() . '</div>
                    </div>
                    <div class="label-value lots-won">
                        <div class="label">' . _hl('Lots won:', $helpSection, 'lots_won', false) . '</div>
                        <div class="value">' . $this->renderNumberWonItems() . '</div>
                    </div>
                    <div class="label-value total-won">
                        <div class="label">' . _hl('Total won:', $helpSection, 'total_won', false) . '</div>
                        <div class="value">' . $totalWon . '</div>
                    </div>
                    <div class="label-value lots-won-percentage">
                        <div class="label">' . _hl('Won:', $helpSection, 'won', false) . '</div>
                        <div class="value">' . $this->renderPercentItemsWon() . '</div>
                    </div>
                    <div class="label-value auctions-won-percentage">
                        <div class="label">' . _hl('Auctions won in:', $helpSection, 'auctions_won_in', false) . '</div>
                        <div class="value">' . $this->renderPercentRegisteredAuctionWon() . '</div>
                    </div>
                    ' . $consignorStats . '
                    <div class="label-value refresh">
                        <div class="value">
                            <span id="' . $cidBtnUpdate . '_ctl" class="qbutton-ctl" >
                                <input type="button" name="' . $cidBtnUpdate . '" id="' . $cidBtnUpdate . '" value="Update" class="button" />
                            </span>
                            <span id="udp08_ctl" class="qwaiticon-ctl" style="display:none;"><span id="udp08" ><img src="/images/spinner_14.gif" width="14" height="14" alt="Please Wait..."/></span></span>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </fieldset>
        </div>

    </div>
</div>
<fieldset>
    <legend>Notes</legend>
    <div>' . $this->renderNotes() . '</div>
</fieldset>';

        $output .= '<div class="user-form">
    <fieldset>
            <legend>Billing information</legend>
             <div class="form user-dashboard">
    ';
        if ($userBilling->ContactType !== Constants\User::CT_NONE) {
            $output .= '
    <div class="label-value ">
                   <div class="label">' . _hl('Contact Type', $helpSectionBill, 'contact_type', false) . '</div>
                   <div class="value">' . Constants\User::CONTACT_TYPE_ENUM[$userBilling->ContactType] . '</div>
                </div>';
        }
        if ($userBilling->FirstName !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('First Name:', $helpSectionBill, 'first_name', false) . '</div>
                   <div class="value">' . $userBilling->FirstName . '</div>
                </div>';
        }
        if ($userBilling->LastName !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('Last Name:', $helpSectionBill, 'last_name', false) . '</div>
                   <div class="value">' . $userBilling->LastName . '</div>
                </div>';
        }
        if ($userBilling->CompanyName !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('Company Name:', $helpSectionBill, 'company_name', false) . '</div>
                   <div class="value">' . $userBilling->CompanyName . '</div>
                </div>';
        }
        if ($userBilling->Phone !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('Phone:', $helpSectionBill, 'phone', false) . '</div>
                   <div class="value">' . $userBilling->Phone . '</div>
                </div>';
        }
        if ($userBilling->Fax !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('Fax:', $helpSectionBill, 'fax', false) . '</div>
                   <div class="value">' . $userBilling->Fax . '</div>
                </div>
    ';
        }
        if ($userBilling->Email !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('Email:', $helpSectionBill, 'email', false) . '</div>
                   <div class="value">' . $userBilling->Email . '</div>
                </div> ';
        }
        if ($userBilling->Country !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('Country:', $helpSectionBill, 'country', false) . '</div>
                   <div class="value">' . $userBilling->Country . '</div>
                </div> ';
        }
        if ($userBilling->Address !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('Address:', $helpSectionBill, 'address', false) . '</div>
                   <div class="value">' . $userBilling->Address . '</div>
                </div> ';
        }
        if ($userBilling->Address2 !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('Address Line 2:', $helpSectionBill, 'address_line_2', false) . '</div>
                   <div class="value">' . $userBilling->Address2 . '</div>
                </div> ';
        }
        if ($userBilling->Address3 !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('Address Line 3:', $helpSectionBill, 'address_line_3', false) . '</div>
                   <div class="value">' . $userBilling->Address3 . '</div>
                </div> ';
        }
        if ($userBilling->City !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('City:', $helpSectionBill, 'city', false) . '</div>
                   <div class="value">' . $userBilling->City . '</div>
                </div> ';
        }
        if ($userBilling->State !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('State:', $helpSectionBill, 'state', false) . '</div>
                   <div class="value">' . $userBilling->State . '</div>
                </div> ';
        }
        if ($userBilling->Zip !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('Zip Code:', $helpSectionBill, 'zip_code', false) . '</div>
                   <div class="value">' . $userBilling->Zip . '</div>
                </div> ';
        }

        if ($this->getEditorUserAdminPrivilegeChecker()->hasPrivilegeForManageCcInfo()) {
            if ($userBilling->CcType) {
                $output .= '

    <div class="label-value">
                   <div class="label">' . _hl('CC Type:', $helpSectionBill, 'cc_type', false) . '</div>
                   <div class="value">' . @Constants\CreditCard::$ccTypes[$userBilling->CcType][0] . '</div>
                </div> ';
            }
            if ($userBilling->CcNumber !== '') {
                $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('CC Number:', $helpSectionBill, 'cc_number', false) . '</div>
                   <div class="value">' . $this->createBlockCipherProvider()->construct()->decrypt($userBilling->CcNumber) . '</div>
                </div> ';
            }
            if ($userBilling->CcExpDate !== '') {
                $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('Expiration Date:', $helpSectionBill, 'expiration_date', false) . '</div>
                   <div class="value">' . $userBilling->CcExpDate . '</div>
                </div> ';
            }
        }
        if ($userBilling->BankName !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('Bank Name:', $helpSectionBill, 'bank_name', false) . '</div>
                   <div class="value">' . $userBilling->BankName . '</div>
                </div> ';
        }
        if ($userBilling->BankRoutingNumber !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('Bank Routing Num:', $helpSectionBill, 'bank_routing_num', false) . '</div>
                   <div class="value">' . $userBilling->BankRoutingNumber . '</div>
                </div> ';
        }
        if ($userBilling->BankAccountNumber !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('Bank Account Num:', $helpSectionBill, 'bank_account_num', false) . '</div>
                   <div class="value">' . $this->createBlockCipherProvider()->construct()->decrypt($userBilling->BankAccountNumber) . '</div>
                </div> ';
        }
        if ($userBilling->BankAccountType !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('Bank Account Type:', $helpSectionBill, 'bank_account_type', false) . '</div>
                   <div class="value">' . $userBilling->BankAccountType . '</div>
                </div> ';
        }
        if ($userBilling->BankAccountName !== '') {
            $output .= '
    <div class="label-value">
                   <div class="label">' . _hl('Bank Account Name:', $helpSectionBill, 'bank_account_name', false) . '</div>
                   <div class="value">' . $userBilling->BankAccountName . '</div>
                </div> ';
        }
        $output .= '
                ' . $this->getCustomFieldBilling($userCustomFieldValues) . '
                </div>

        </fieldset>
    </div>';
        return $output;
    }

    /**
     * @param array $userCustomFieldValues
     * @return string
     */
    protected function getCustomFieldBilling(array $userCustomFieldValues): string
    {
        $customFieldBilling = '';
        foreach ($userCustomFieldValues as $key => $value) {
            if (!$value) {
                continue;
            }
            $customFieldBilling .= '<div class="label-value">
                   <div class="label">' . $key . '</div>
                   <div class="value">' . $value . '</div>
                </div>';
        }
        return $customFieldBilling;
    }

    /**
     * @param string $field
     * @return string
     */
    protected function getNumberStatsHtml(string $field): string
    {
        $global = '';
        $url = $this->dashboardUrl . '/' .
            $this->getUserAccountStatisticProducer()->userAccountStatsFieldsBoard[$field];
        $backUrl = $this->getParamFetcherForGet()->getBackUrl();
        if ($backUrl) {
            $url = $this->getBackUrlParser()->replace($url, $backUrl);
        }

        $number = isset($this->userAccountStats[$field]) ?
            (int)$this->userAccountStats[$field] : 0;
        $output = <<<HTML
            <a href="{$url}" target="_blank">{$number}</a>
HTML;

        if ($this->isShareStats) {
            $number = isset($this->userAccountStatsTotal[$field]) ?
                (int)$this->userAccountStatsTotal[$field] : 0;
            $global = ' (' . $number . ')';
            $hasAccess = $this->createApplicationAccessChecker()
                ->isCrossDomainAdminOnMainAccountForMultipleTenantOrAdminForSingleTenant(
                    $this->getEditorUserId(),
                    $this->getSystemAccountId(),
                    true
                );
            if ($hasAccess) {
                $url .= '/global/yes';
                $global = <<<HTML
            <a href="{$url}" target="_blank">{$global}</a>
HTML;
            }
        }

        return $output . $global;
    }

    /**
     * @param string $field
     * @return string
     */
    protected function getPercentageStatsHtml(string $field): string
    {
        $global = '';

        $percentage = isset($this->userAccountStats[$field]) ?
            (float)$this->userAccountStats[$field] : 0;
        $percentage = $this->getNumberFormatter()->formatPercent($percentage);
        $output = <<<HTML
{$percentage}%
HTML;
        if ($this->isShareStats) {
            $percentage = isset($this->userAccountStatsTotal[$field]) ?
                (float)$this->userAccountStatsTotal[$field] : 0;
            $percentage = $this->getNumberFormatter()->formatPercent($percentage);
            $global = <<<HTML
 ({$percentage}%)
HTML;
        }

        return $output . $global;
    }

    /**
     * @return string
     */
    public function renderNumberRegisteredAuction(): string
    {
        return $this->getNumberStatsHtml('registered_auctions_num');
    }

    /**
     * @return string
     */
    public function renderPercentRegisteredAuctionWon(): string
    {
        return $this->getPercentageStatsHtml('auctions_won_perc');
    }

    /**
     * @return string
     */
    public function renderNumberParticipatedAuction(): string
    {
        return $this->getNumberStatsHtml('participated_auctions_num');
    }

    /**
     * @return string
     */
    public function renderStatsLastUpdated(): string
    {
        $output = $this->getDateStatsHtml('calculated_on');
        $calculatedOn = $this->userAccountStats['calculated_on'] ?? null;
        $expiredOn = $this->userAccountStats['expired_on'] ?? null;
        if ($calculatedOn && ($calculatedOn > $expiredOn)) {
            $output .= ' (current)';
        } else {
            $expiredRows = $this->createUserAccountStatisticLoader()->loadExpiredQueue();
            $total = count($expiredRows);
            $index = $this->createUserAccountStatisticLoader()->detectQueueIndex($expiredRows, $this->getUser()->Id, $this->getUser()->AccountId);
            $output .= " (expired {$this->getDateStatsHtml('expired_on')}) (queue {$index}/{$total} records)";
        }
        return $output;
    }

    /**
     * @return string
     */
    public function renderPercentParticipatedAuction(): string
    {
        return $this->getPercentageStatsHtml('participated_auctions_perc');
    }

    /**
     * @return string
     */
    public function renderNumberWonItems(): string
    {
        return $this->getNumberStatsHtml('lots_won_num');
    }

    /**
     * @return string
     */
    public function renderNumberBidOnItems(): string
    {
        return $this->getNumberStatsHtml('lots_bid_on_num');
    }

    /**
     * @return string
     */
    public function renderPercentItemsWon(): string
    {
        return $this->getPercentageStatsHtml('lots_won_perc');
    }

    /**
     * @return string
     */
    public function renderNumberWatchlistItems(): string
    {
        return $this->getNumberStatsHtml('watchlist_items_num');
    }

    /**
     * @return string
     */
    public function renderPercentWatchlistWon(): string
    {
        return $this->getPercentageStatsHtml('watchlist_items_won_perc');
    }

    /**
     * @return string
     */
    public function renderPercentWatchlistBidOn(): string
    {
        return $this->getPercentageStatsHtml('watchlist_items_bid_perc');
    }

    /**
     * @return string
     */
    public function renderNumberConsignedItems(): string
    {
        return $this->getNumberStatsHtml('lots_consigned_num');
    }

    /**
     * @return string
     */
    public function renderNumberSoldItems(): string
    {
        return $this->getNumberStatsHtml('lots_consigned_sold_num');
    }

    /**
     * @return array
     */
    public function getAmountPerCurrency(): array
    {
        return $this->userAccountStatsCurrency;
    }

    /**
     * @param string $field
     * @return string
     */
    protected function getDateStatsHtml(string $field): string
    {
        $global = '';
        $url = $this->dashboardUrl . '/' .
            ($this->getUserAccountStatisticProducer()->userAccountStatsFieldsBoard[$field] ?? '');
        $backUrl = $this->getParamFetcherForGet()->getBackUrl();
        if ($backUrl) {
            $url = $this->getBackUrlParser()->replace($url, $backUrl);
        }
        $statDate = isset($this->userAccountStats[$field])
            ? (string)$this->userAccountStats[$field] : null;
        if ($statDate) {
            $date = new DateTime($statDate);
            $date = $this->getDateHelper()->convertUtcToSys($date);
            $dateFormatted = $this->getDateHelper()->formattedDate($date);
            $output = <<<HTML
<a href="{$url}" target="_blank">{$dateFormatted}</a>
HTML;
        } else {
            $output = <<<HTML
n/a
HTML;
        }

        if ($this->isShareStats) {
            $statDate = isset($this->userAccountStatsTotal[$field]) ?
                (string)$this->userAccountStatsTotal[$field] : null;

            if ($statDate) {
                $date = new DateTime($statDate);
                $date = $this->getDateHelper()->convertUtcToSys($date);
                $dateFormatted = $this->getDateHelper()->formattedDate($date);
                $global = ' (' . $dateFormatted . ')';
                $hasAccess = $this->createApplicationAccessChecker()
                    ->isCrossDomainAdminOnMainAccountForMultipleTenantOrAdminForSingleTenant(
                        $this->getEditorUserId(),
                        $this->getSystemAccountId(),
                        true
                    );
                if ($hasAccess) {
                    $url .= '/global/yes';
                    $global = <<<HTML
 <a href="{$url}" target="_blank">{$global}</a>
HTML;
                }
            }
        }

        return $output . $global;
    }

    /**
     * @return string
     */
    public function renderLastDateRegistered(): string
    {
        return $this->getDateStatsHtml('last_date_auction_registered');
    }

    /**
     * @return string
     */
    public function renderLastDateBidOn(): string
    {
        return $this->getDateStatsHtml('last_date_bid');
    }

    /**
     * @return string
     */
    public function renderLastDateWonOn(): string
    {
        return $this->getDateStatsHtml('last_date_won');
    }

    /**
     * @return string
     */
    public function renderLastDateLoggedIn(): string
    {
        $field = 'last_date_logged_in';
        $statDate = isset($this->userAccountStats[$field])
            ? (string)$this->userAccountStats[$field] : null;
        if ($statDate) {
            $date = new DateTime($statDate);
            $date = $this->getDateHelper()->convertUtcToSys($date);
            $dateFormatted = $this->getDateHelper()->formattedDate($date);
            $output = <<<HTML
{$dateFormatted}
HTML;
        } else {
            $output = <<<HTML
n/a
HTML;
        }

        return $output;
    }

    /**
     * @return string
     */
    public function renderNotes(): string
    {
        return nl2br($this->getUserInfo()->Note ?? '');
    }

    /**
     * @return bool
     */
    public function shareStats(): bool
    {
        return $this->isShareStats;
    }

    public function initUserAccountData(): void
    {
        if ($this->getUserId()) {
            $userAccountStatisticLoader = $this->createUserAccountStatisticLoader()
                ->setUserId($this->getUserId())
                ->setAccountId($this->getSystemAccountId());

            $this->userAccountStats = $userAccountStatisticLoader->loadUserAccountStats();
            $userAccountStatsCurrencies = $userAccountStatisticLoader->getUserAccountStatsCurrency();

            foreach ($userAccountStatsCurrencies as $statsCurrencies) {
                $currSign = $statsCurrencies['currency_sign'];
                $this->userAccountStatsCurrency[$currSign] = [
                    'lots_bid_on_amt' => $statsCurrencies['lots_bid_on_amt'],
                    'lots_won_amt' => $statsCurrencies['lots_won_amt'],
                    'lots_consigned_sold_amt' => $statsCurrencies['lots_consigned_sold_amt'],
                    'watchlist_items_won_amt' => $statsCurrencies['watchlist_items_won_amt'],
                    'watchlist_items_bid_amt' => $statsCurrencies['watchlist_items_bid_amt'],
                ];
            }

            if ($this->isShareStats) {
                $this->userAccountStatsTotal = $userAccountStatisticLoader->loadUserAccountStatsTotal();
                $userAccountStatsCurrencies = $userAccountStatisticLoader->getUserAccountStatsCurrencyTotal();
                foreach ($userAccountStatsCurrencies as $statsCurrencies) {
                    $currSign = $statsCurrencies['currency_sign'];
                    if (isset($this->userAccountStatsCurrency[$currSign])) {
                        $this->userAccountStatsCurrency[$currSign]['lots_bid_on_amt_total']
                            = $statsCurrencies['lots_bid_on_amt'];
                        $this->userAccountStatsCurrency[$currSign]['lots_won_amt_total']
                            = $statsCurrencies['lots_won_amt'];
                        $this->userAccountStatsCurrency[$currSign]['lots_consigned_sold_amt_total']
                            = $statsCurrencies['lots_consigned_sold_amt'];
                        $this->userAccountStatsCurrency[$currSign]['watchlist_items_won_amt_total']
                            = $statsCurrencies['watchlist_items_won_amt'];
                        $this->userAccountStatsCurrency[$currSign]['watchlist_items_bid_amt_total']
                            = $statsCurrencies['watchlist_items_bid_amt'];
                    } else {
                        $this->userAccountStatsCurrency[$currSign] = [
                            'lots_bid_on_amt' => 0,
                            'lots_won_amt' => 0,
                            'lots_consigned_sold_amt' => 0,
                            'watchlist_items_won_amt' => 0,
                            'watchlist_items_bid_amt' => 0,
                            'lots_bid_on_amt_total' => $statsCurrencies['lots_bid_on_amt'],
                            'lots_won_amt_total' => $statsCurrencies['lots_won_amt'],
                            'lots_consigned_sold_amt_total' => $statsCurrencies['lots_consigned_sold_amt'],
                            'watchlist_items_won_amt_total' => $statsCurrencies['watchlist_items_won_amt'],
                            'watchlist_items_bid_amt_total' => $statsCurrencies['watchlist_items_bid_amt'],
                        ];
                    }
                }
            }
            $url = $this->getUrlBuilder()->build(
                AnySingleUserUrlConfig::new()->forWeb(Constants\Url::A_USERS_DASHBOARD, $this->getUserId())
            );
            $this->dashboardUrl = sprintf('%s/%s', $url, Constants\UrlParam::R_BOARD);
        }
    }
}
