<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\Notify\Sms\Internal;

use Auction;
use AuctionLotItem;
use LotItem;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Sms\Template\Placeholder\Auction\AuctionPlaceholderRendererCreateTrait;
use Sam\Sms\Template\Placeholder\AuctionLot\AuctionLotPlaceholderRendererCreateTrait;
use Sam\Sms\Template\Placeholder\LotItem\LotItemPlaceholderRendererCreateTrait;
use Sam\Sms\Template\Placeholder\OutbidBidder\OutbidBidderPlaceholderRendererCreateTrait;
use Sam\Sms\Template\SmsTemplateRendererCreateTrait;
use User;

/**
 * Class TemplateRenderer
 * @package Sam\Bidding\Notify\Sms\Internal
 * @internal
 */
class TemplateRenderer extends CustomizableClass
{
    use AuctionLotPlaceholderRendererCreateTrait;
    use AuctionPlaceholderRendererCreateTrait;
    use LotItemPlaceholderRendererCreateTrait;
    use OutbidBidderPlaceholderRendererCreateTrait;
    use SettingsManagerAwareTrait;
    use SmsTemplateRendererCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Render text message notification template with lot item, auction lot, auction, and user placeholders
     *
     * @param LotItem $lotItem
     * @param AuctionLotItem $auctionLot
     * @param Auction $auction
     * @param User $user
     * @return string
     */
    public function render(LotItem $lotItem, AuctionLotItem $auctionLot, Auction $auction, User $user): string
    {
        $template = (string)$this->getSettingsManager()
            ->get(Constants\Setting::TEXT_MSG_API_OUTBID_NOTIFICATION, $auction->AccountId);

        $smsTemplateRenderer = $this->createSmsTemplateRenderer()->construct(
            [
                $this->createLotItemPlaceholderRenderer()->construct($lotItem),
                $this->createAuctionLotPlaceholderRenderer()->construct($auctionLot),
                $this->createAuctionPlaceholderRenderer()->construct($auction),
                $this->createOutbidBidderPlaceholderRenderer()->construct($user),
            ]
        );
        $output = $smsTemplateRenderer->render($template);
        return $output;
    }
}
