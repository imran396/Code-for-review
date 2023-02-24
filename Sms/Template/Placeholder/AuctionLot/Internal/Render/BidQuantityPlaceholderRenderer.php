<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render;

use AuctionLotItem;
use InvalidArgumentException;
use Sam\Core\Service\CustomizableClass;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\PlaceholderKey;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\Internal\Load\DataProviderAwareTrait;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;

/**
 * Class BidQuantityPlaceholderRenderer
 * @package Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render
 * @internal
 */
class BidQuantityPlaceholderRenderer extends CustomizableClass implements PlaceholderRendererInterface
{
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
        return [PlaceholderKey::BID_QUANTITY];
    }

    public function render(SmsTemplatePlaceholder $placeholder, AuctionLotItem $auctionLot): string
    {
        if (!in_array($placeholder->key, $this->getApplicablePlaceholderKeys(), true)) {
            throw new InvalidArgumentException("Not applicable placeholder '{$placeholder->key}'");
        }

        return (string)$this->getDataProvider()->countBids($auctionLot->LotItemId, $auctionLot->AuctionId, true);
    }
}
