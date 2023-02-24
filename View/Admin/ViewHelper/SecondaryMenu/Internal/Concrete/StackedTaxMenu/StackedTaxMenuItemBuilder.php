<?php
/**
 * SAM-10940: Stacked Tax. Add to admin menu (Stage-1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           July 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\StackedTaxMenu;

use Sam\Application\Url\Build\Config\Base\OneStringParamUrlConfig;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SystemParameterMenu\Internal\Load\DataProviderCreateTrait;

/**
 * Class SystemParameterMenuBuilder
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\StackedTaxMenu
 */
class StackedTaxMenuItemBuilder extends CustomizableClass
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


    public function build(): array
    {
        $urlBuilder = $this->getUrlBuilder();
        $adminTranslator = $this->getAdminTranslator();
        $items = [
            [
                'label' => $adminTranslator->trans('secondary_menu.stacked_tax.tax_schema_list.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_TAX_SCHEMA_LIST)
                ),
                'subUrls' => [
                    $urlBuilder->build(
                        ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_TAX_SCHEMA_CREATE)
                    ),
                    $urlBuilder->build(
                        OneStringParamUrlConfig::new()->forWeb(Constants\Url::A_TAX_SCHEMA_EDIT, '')
                    ),
                ],
            ],
            [
                'label' => $adminTranslator->trans('secondary_menu.stacked_tax.tax_definition.label'),
                'url' => $urlBuilder->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_TAX_DEFINITION_LIST)
                ),
                'subUrls' => [
                    $urlBuilder->build(
                        ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_TAX_DEFINITION_CREATE)
                    ),
                    $urlBuilder->build(
                        OneStringParamUrlConfig::new()->forWeb(Constants\Url::A_TAX_DEFINITION_EDIT, '')
                    ),
                ],
            ],
        ];
        return [
            'items' => array_values(array_filter($items)),
        ];
    }
}
