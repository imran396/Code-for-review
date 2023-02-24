<?php
/**
 * Placeholders related data for translation and db access
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Jul 2, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\SeoUrl;

use Sam\Core\Constants;

/**
 * Class ConfigManager
 * @package Sam\Details
 */
class ConfigManager extends \Sam\Details\Auction\Base\ConfigManager
{
    /**
     * @var string[]
     */
    protected ?array $availableKeys = [
        Constants\AuctionDetail::PL_ACCOUNT_COMPANY,
        Constants\AuctionDetail::PL_ACCOUNT_ID,
        Constants\AuctionDetail::PL_ACCOUNT_NAME,
        Constants\AuctionDetail::PL_COUNTRY,
        Constants\AuctionDetail::PL_CURRENCY_SIGN,
        Constants\AuctionDetail::PL_DAYS,
        Constants\AuctionDetail::PL_DESCRIPTION,
        Constants\AuctionDetail::PL_EVENT_ID,
        Constants\AuctionDetail::PL_ID,
        Constants\AuctionDetail::PL_INVOICE_LOCATION_ADDRESS,
        Constants\AuctionDetail::PL_INVOICE_LOCATION_NAME,
        Constants\AuctionDetail::PL_NAME,
        Constants\AuctionDetail::PL_SALE_NO,
        Constants\AuctionDetail::PL_TYPE,
        Constants\AuctionDetail::PL_TYPE_LANG,
    ];

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function initInstance(): static
    {
        $this->enabledTypes[Constants\Placeholder::LANG_LABEL] = false;
        $this->enabledTypes[Constants\Placeholder::BEGIN_END] = false;
        return $this;
    }

    /**
     * @return static
     */
    public function construct(): static
    {
        $this->enableAuctionCustomFields(true);
        return parent::construct();
    }
}
