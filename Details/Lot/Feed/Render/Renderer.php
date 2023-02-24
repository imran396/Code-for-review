<?php
/**
 * Rendering methods for placeholders of Lot Details output
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Feb 16, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Custom methods can be used there or in customized class (SAM-1573)
 *
 * Optional method called when rendering the custom lot item field value
 * param integer $type lot_item_cust_field.type
 * param integer $numeric lot_item_cust_data.numeric
 * param integer $text lot_item_cust_data.text
 * param mixed $parameters lot_item_cust_field.parameters
 * param integer $auctionId auction.id optional
 * return string the rendered value
 * public function LotCustomField_{Field name}_Render($type, $numeric, $text, $parameters, $auctionId = null)
 *
 * {Field name} - Camel cased name of custom field (see TextTransformer::toCamelCase())
 */

namespace Sam\Details\Lot\Feed\Render;

use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;

/**
 * Class Renderer
 * @package Sam\Details
 */
class Renderer extends \Sam\Details\Lot\Base\Render\Renderer
{
    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function renderLotUrl(array $row): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveLotDetailsUrlConfig::new()->forDomainRule(
                (int)$row['id'],
                (int)$row['auction_id'],
                $row['lot_seo_url'],
                [UrlConfigConstants::OP_ACCOUNT_ID => (int)$row['account_id']]
            )
        );
    }
}
