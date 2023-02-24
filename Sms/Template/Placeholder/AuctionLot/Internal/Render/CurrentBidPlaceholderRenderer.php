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
use Sam\Bidding\CurrentAbsenteeBid\CurrentAbsenteeBidCalculatorCreateTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\PlaceholderKey;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\Internal\Load\DataProviderAwareTrait;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;

/**
 * Class CurrentBidPlaceholderRenderer
 * @package Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render
 * @internal
 */
class CurrentBidPlaceholderRenderer extends CustomizableClass implements PlaceholderRendererInterface
{
    use CurrentAbsenteeBidCalculatorCreateTrait;
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
        return [PlaceholderKey::CURRENT_BID];
    }

    public function render(SmsTemplatePlaceholder $placeholder, AuctionLotItem $auctionLot): string
    {
        if (!in_array($placeholder->key, $this->getApplicablePlaceholderKeys(), true)) {
            throw new InvalidArgumentException("Not applicable placeholder '{$placeholder->key}'");
        }

        $currentBidAmount = $this->getDataProvider()->loadCurrentBidAmount($auctionLot->Id, true);
        if (Floating::eq($currentBidAmount, 0)) {
            $lotItem = $this->getDataProvider()->loadLotItem($auctionLot->LotItemId);
            $currentBidAmount = (float)($lotItem->StartingBid ?? 0);

            $auction = $this->getDataProvider()->loadAuction($auctionLot->AuctionId, true);
            if ($auction && $auction->isLiveOrHybrid()) {
                $amount = $this->createCurrentAbsenteeBidCalculator()
                    ->setLotItem($lotItem)
                    ->setAuction($auction)
                    ->calculate();
                if (Floating::gt($amount, 0)) {
                    $currentBidAmount = $amount;
                }
            }
        }
        return (string)$currentBidAmount;
    }
}
