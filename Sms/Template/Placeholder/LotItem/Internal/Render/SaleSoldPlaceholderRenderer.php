<?php
/**
 * SAM
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\LotItem\Internal\Render;

use LotItem;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Sms\Template\Placeholder\LotItem\Internal\PlaceholderKey;
use Sam\Sms\Template\Placeholder\LotItem\Internal\Render\Internal\Load\DataProviderAwareTrait;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;

/**
 * Class SaleSoldPlaceholderRenderer
 * @package Sam\Sms\Template\Placeholder\LotItem\Internal\Render
 * @internal
 */
class SaleSoldPlaceholderRenderer extends CustomizableClass implements PlaceholderRendererInterface
{
    use AuctionRendererAwareTrait;
    use DataProviderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getApplicablePlaceholderKeys(): array
    {
        return [PlaceholderKey::SALE_SOLD_IN, PlaceholderKey::SALE_SOLD_IN_NO];
    }

    public function render(SmsTemplatePlaceholder $placeholder, LotItem $lotItem): string
    {
        switch ($placeholder->key) {
            case PlaceholderKey::SALE_SOLD_IN:
                $auction = $this->getDataProvider()->loadAuction($lotItem->AuctionId, true);
                if (!$auction) {
                    return '';
                }
                return $this->getAuctionRenderer()->renderName($auction);
            case PlaceholderKey::SALE_SOLD_IN_NO:
                $auction = $this->getDataProvider()->loadAuction($lotItem->AuctionId, true);
                if (!$auction) {
                    return '';
                }
                return $this->getAuctionRenderer()->renderSaleNo($auction);
            default:
                throw new \InvalidArgumentException("Not applicable placeholder '{$placeholder->key}'");
        }
    }
}
