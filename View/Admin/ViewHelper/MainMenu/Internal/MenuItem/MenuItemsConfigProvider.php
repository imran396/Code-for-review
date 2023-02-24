<?php
/**
 * SAM-7717: Refactor admin menu tabs rendering module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\MainMenu\Internal\MenuItem;

use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Tax\StackedTax\Feature\StackedTaxFeatureAvailabilityCheckerCreateTrait;

/**
 * Class MenuItemsConfigProvider
 * @package Sam\View\Admin\ViewHelper\MainMenu\Internal\MenuItem
 * @internal
 */
class MenuItemsConfigProvider extends CustomizableClass
{
    use SettingsManagerAwareTrait;
    use StackedTaxFeatureAvailabilityCheckerCreateTrait;
    use UrlBuilderAwareTrait;

    /**
     * @var array
     */
    protected array $menuItemsConfig = [
        'home' => [
            'privilege' => Constants\AdminPrivilege::NONE,
            'controllers' => [Constants\AdminRoute::C_MANAGE_HOME],
            'url' => Constants\Url::A_ADMIN_HOME,
        ],
        'auctions' => [
            'privilege' => Constants\AdminPrivilege::MANAGE_AUCTIONS,
            'controllers' => [Constants\AdminRoute::C_MANAGE_AUCTIONS],
            'url' => Constants\Url::A_AUCTIONS_LIST,
        ],
        'inventory' => [
            'privilege' => Constants\AdminPrivilege::MANAGE_INVENTORY,
            'controllers' => [Constants\AdminRoute::C_MANAGE_INVENTORY],
            'url' => Constants\Url::A_INVENTORY,
        ],
        'users' => [
            'privilege' => Constants\AdminPrivilege::MANAGE_USERS,
            'controllers' => [Constants\AdminRoute::C_MANAGE_USERS],
            'url' => Constants\Url::A_USERS_LIST,
        ],
        'invoices' => [
            'privilege' => Constants\AdminPrivilege::MANAGE_INVOICES,
            'controllers' => [
                Constants\AdminRoute::C_MANAGE_INVOICES,
                Constants\AdminRoute::C_MANAGE_STACKED_TAX_INVOICE,
            ],
            'url' => Constants\Url::A_INVOICES_LIST
        ],
        'settlements' => [
            'privilege' => Constants\AdminPrivilege::MANAGE_SETTLEMENTS,
            'controllers' => [
                Constants\AdminRoute::C_MANAGE_SETTLEMENTS,
                Constants\AdminRoute::C_MANAGE_SETTLEMENT_CHECK,
            ],
            'url' => Constants\Url::A_SETTLEMENTS_LIST
        ],
        'reports' => [
            'privilege' => Constants\AdminPrivilege::MANAGE_REPORTS,
            'controllers' => [
                Constants\AdminRoute::C_MANAGE_REPORTS,
                Constants\AdminRoute::C_MANAGE_SALES_STAFF_REPORT,
                Constants\AdminRoute::C_MANAGE_MAILING_LIST_REPORT,
            ],
            'url' => Constants\Url::A_REPORTS_LIST
        ],
        'settings' => [
            'privilege' => Constants\AdminPrivilege::MANAGE_SETTINGS,
            'controllers' => [
                Constants\AdminRoute::C_MANAGE_BUYER_GROUP,
                Constants\AdminRoute::C_MANAGE_CONSIGNOR_COMMISSION_FEE,
                Constants\AdminRoute::C_MANAGE_COUPON,
                Constants\AdminRoute::C_MANAGE_CUSTOM_FIELD,
                Constants\AdminRoute::C_MANAGE_CUSTOM_TEMPLATE,
                Constants\AdminRoute::C_MANAGE_FEED,
                Constants\AdminRoute::C_MANAGE_LOCATION,
                Constants\AdminRoute::C_MANAGE_SYSTEM_PARAMETER,
                Constants\AdminRoute::C_MANAGE_BUYERS_PREMIUM,
                Constants\AdminRoute::C_MANAGE_ACCOUNT,
                Constants\AdminRoute::C_MANAGE_BID_INCREMENT,
                Constants\AdminRoute::C_MANAGE_AUCTIONEER,
                Constants\AdminRoute::C_MANAGE_EMAIL_TEMPLATE,
                Constants\AdminRoute::C_MANAGE_SITE_CONTENT,
                Constants\AdminRoute::C_MANAGE_TRANSLATION,
                Constants\AdminRoute::C_MANAGE_SYNC,
            ],
            'url' => Constants\Url::A_MANAGE_SYSTEM_PARAMETER_DEFAULT
        ],
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->adjustInvoiceUrl();
        return $this;
    }

    /**
     * @return array
     */
    public function getMenuItemsConfig(): array
    {
        return $this->menuItemsConfig;
    }

    /**
     * Replace with link to the Stacked Tax invoicing, when feature enabled and configured in settings.
     * @return void
     */
    protected function adjustInvoiceUrl(): void
    {
        $invoiceTaxDesignationStrategy = $this->getSettingsManager()
            ->getForSystem(Constants\Setting::INVOICE_TAX_DESIGNATION_STRATEGY);
        $isStackedTaxEnabled = $this->createStackedTaxFeatureAvailabilityChecker()->isEnabled();
        if (
            $isStackedTaxEnabled
            && $invoiceTaxDesignationStrategy === Constants\Invoice::TDS_STACKED_TAX
        ) {
            $this->menuItemsConfig['invoices']['url'] = Constants\Url::A_STACKED_TAX_INVOICE_LIST;
        }
    }
}
