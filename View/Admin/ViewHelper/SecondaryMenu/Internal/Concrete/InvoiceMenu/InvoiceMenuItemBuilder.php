<?php
/**
 * SAM-11079: Stacked Tax. Tax aggregation. Admin Invoice List page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           10-24, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\InvoiceMenu;

use Sam\Application\Url\Build\Config\Base\OneStringParamUrlConfig;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Tax\StackedTax\Feature\StackedTaxFeatureAvailabilityCheckerCreateTrait;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Common\SecondaryMenuConstants;

/**
 * Class SettlementMenuItemBuilder
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SettlementMenu
 */
class InvoiceMenuItemBuilder extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use StackedTaxFeatureAvailabilityCheckerCreateTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function build(): array
    {
        $items = $this->buildMenuItems();
        return [
            'items' => array_values(array_filter($items)),
            'position' => SecondaryMenuConstants::POSITION_LEFT,
        ];
    }

    protected function buildMenuItems(): array
    {
        if (!$this->createStackedTaxFeatureAvailabilityChecker()->isEnabled()) {
            return [];
        }

        $urlBuilder = $this->getUrlBuilder();
        $adminTranslator = $this->getAdminTranslator();
        $items = [
            [
                'label' => $adminTranslator->trans('secondary_menu.invoice.legacy.label'),
                'title' => $adminTranslator->trans('secondary_menu.invoice.legacy.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_INVOICES_LIST)
                ),
                'subUrls' => [
                    $urlBuilder->build(
                        OneStringParamUrlConfig::new()->forWeb(Constants\Url::A_INVOICES_EDIT, '')
                    )
                ],
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.invoice.stacked_tax.label'),
                'title' => $adminTranslator->trans('secondary_menu.invoice.stacked_tax.title'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_STACKED_TAX_INVOICE_LIST)
                ),
                'subUrls' => [
                    $urlBuilder->build(
                        OneStringParamUrlConfig::new()->forWeb(Constants\Url::A_STACKED_TAX_INVOICE_EDIT, '')
                    ),
                    $urlBuilder->build(
                        OneStringParamUrlConfig::new()->forWeb(Constants\Url::A_STACKED_TAX_INVOICE_ITEM_EDIT, '')
                    ),
                    $urlBuilder->build(
                        OneStringParamUrlConfig::new()->forWeb(Constants\Url::A_STACKED_TAX_INVOICE_SERVICE_FEE_EDIT, '')
                    ),
                    $urlBuilder->build(
                        OneStringParamUrlConfig::new()->forWeb(Constants\Url::A_STACKED_TAX_INVOICE_PAYMENT_EDIT, '')
                    ),
                    $urlBuilder->build(
                        OneStringParamUrlConfig::new()->forWeb(Constants\Url::A_STACKED_TAX_INVOICE_PAYMENT_CREATE, '')
                    ),
                ],
            ],
        ];
        return $items;
    }
}
