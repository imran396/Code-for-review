<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BuyerPremiumMenu;

use Sam\Application\Url\Build\Config\Base\OneStringParamUrlConfig;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BuyerPremiumMenu\Internal\Load\DataProviderCreateTrait;

/**
 * Class BuyerPremiumMenuItemBuilder
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BuyerPremiumMenu
 */
class BuyerPremiumMenuItemBuilder extends CustomizableClass
{
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
     * @param int $systemAccountId
     * @return array
     */
    public function build(int $systemAccountId): array
    {
        return [
            'items' => $this->getBuyersPremiumLabels($systemAccountId),
        ];
    }

    /**
     * @param int $accountId
     * @return array<int, array<string, string>>
     */
    protected function getBuyersPremiumLabels(int $accountId): array
    {
        $buyersPremiums = [];
        $rows = $this->createDataProvider()->loadAccountBuyersPremiums($accountId);
        foreach ($rows as $row) {
            $url = $this->getUrlBuilder()->build(
                OneStringParamUrlConfig::new()->forWeb(
                    Constants\Url::A_MANAGE_BUYERS_PREMIUM_EDIT,
                    $row['short_name']
                )
            );
            $buyersPremiums[] = [
                'label' => ee($row['name']),
                'url' => $url,
            ];
        }
        $newBpRules = [
            [
                'label' => 'Create new bp rule',
                'url' => $this->getUrlBuilder()->build(
                    ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_BUYERS_PREMIUM_CREATE)
                ),
            ],
        ];
        $output = array_merge($buyersPremiums, $newBpRules);
        return $output;
    }
}
