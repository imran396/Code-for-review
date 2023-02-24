<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\ReportMenu;

use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Common\SecondaryMenuConstants;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\ReportMenu\Internal\Load\DataProviderCreateTrait;

/**
 * Class ReportMenuItemBuilder
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\ReportMenu
 */
class ReportMenuItemBuilder extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use DataProviderCreateTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $editorUserId
     * @return array
     */
    public function build(?int $editorUserId): array
    {
        $adminTranslator = $this->getAdminTranslator();
        $urlBuilder = $this->getUrlBuilder();
        $items = [
            [
                'label' => $adminTranslator->trans('secondary_menu.reports.consignors.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_REPORTS_CONSIGNORS)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.reports.sales.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_REPORTS_SALES)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.reports.payment.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_REPORTS_PAYMENT)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.reports.tax.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_REPORTS_TAX)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.reports.referrers.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_REPORTS_REFERRERS)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.reports.document_views.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_REPORTS_DOCUMENT_VIEWS)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.reports.under_bidders.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_REPORTS_UNDER_BIDDERS)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.reports.audit_trail.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_REPORTS_AUDIT_TRAIL)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.reports.lot_t_and_c.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_REPORTS_SPECIAL_TERMS)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.reports.custom_lots.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_REPORTS_CUSTOM_LOTS)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.reports.internal_notes.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_REPORTS_INTERNAL_NOTE)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.reports.auction.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_REPORTS_AUCTIONS)
                )
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.reports.mailing_lists.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_REPORTS_MANAGE_MAILING_LIST)
                )
            ],
        ];

        $hasAccessForReporticoManagement = $this->createDataProvider()->hasAccessForReporticoManagement($editorUserId, true);

        if ($hasAccessForReporticoManagement) {
            $items[] = [
                'label' => $adminTranslator->trans('secondary_menu.reports.custom.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_REPORTS_BASIC)
                )
            ];
        }

        return [
            'items' => $items,
            'position' => SecondaryMenuConstants::POSITION_LEFT,
        ];
    }
}
