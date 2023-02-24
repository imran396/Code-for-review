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

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BuyerPremiumMenu\Internal\Load;

use Sam\BuyersPremium\Load\BuyersPremiumLoaderCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BuyerPremiumMenu\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use BuyersPremiumLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return array [['name' => string, 'short_name' => string], ...]
     */
    public function loadAccountBuyersPremiums(int $accountId, bool $isReadOnlyDb = false): array
    {
        return $this->createBuyersPremiumLoader()->loadSelectedByAccountId(
            ['name', 'short_name'],
            $accountId,
            $isReadOnlyDb
        );
    }

}
