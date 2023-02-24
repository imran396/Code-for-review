<?php
/**
 * SAM-11950: Stacked Tax - Stage 2: Locations and tax authority: Display Geo Taxes in invoice
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 16, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\TaxSchema\Internal\TaxCountry\Internal\Load;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;

class DataProvider extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadAuctionTaxCountry(?int $auctionId, bool $isReadOnlyDb = false): string
    {
        return $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb)?->TaxDefaultCountry ?? '';
    }

    public function loadAccountTaxCountry(?int $accountId): string
    {
        return $this->getSettingsManager()->get(Constants\Setting::SAM_TAX_DEFAULT_COUNTRY, $accountId);
    }

}
