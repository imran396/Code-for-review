<?php
/**
 * SAM-5344: Rtb rendering helper
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           8/11/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Render;

use Auction;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;

/**
 * Class RtbRenderer
 */
class RtbRenderer extends CustomizableClass
{
    use LotAmountRendererFactoryCreateTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use TranslatorAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create string for rendering auctioneer message, that we show in message center
     * @param string $message
     * @param Auction|null $auction null leads to empty '' result, unknown auction
     * @return string
     */
    public function renderAuctioneerMessage(string $message, ?Auction $auction): string
    {
        if (
            !$message
            || !$auction
        ) {
            return '';
        }

        $langAuctioneer = $this->getTranslator()->translateForRtb('BIDDERCLIENT_AUCTIONEER', $auction);
        $messageTpl = "<span class=\"auc-lbl\">{$langAuctioneer}: </span> %s<br />\n";
        $output = sprintf($messageTpl, $message);
        return $output;
    }

    /**
     * Highlighted with red "Auctioneer: message"
     * @param string $message
     * @param Auction|null $auction
     * @return string
     */
    public function renderAuctioneerWarningMessage(string $message, ?Auction $auction): string
    {
        $auctioneerMessage = $message
            ? '<span style="text-decoration:blink;color:#ff0000;">' . $message . '</span>'
            : '';
        return $this->renderAuctioneerMessage($auctioneerMessage, $auction);
    }

    /**
     * Return message, that we send, when bid accepting is denied
     * @param int|null $bidByUserId
     * @param string $clarification
     * @return string
     */
    public function renderMessageForDeniedBidAccepting(
        ?int $bidByUserId,
        string $clarification
    ): string {
        $message = "Accepting online bid for user id {$bidByUserId} has been denied.";
        $statusHtml = "<span style=\"color:#ff0000;\">{$message} {$clarification}</span>";
        return $statusHtml;
    }

    /**
     * Return quantity x money info for messenger
     * @param Auction|null $auction null leads to empty '' result, absent auction
     * @param int|null $lotItemId
     * @param float|null $quantity null leads to empty '' result, unknown quantity
     * @param bool $isQuantityXMoney
     * @return string
     */
    public function renderQuantityHtml(?Auction $auction, ?int $lotItemId, ?float $quantity, bool $isQuantityXMoney): string
    {
        if (
            !$quantity
            || !$lotItemId
            || !$isQuantityXMoney
            || !$auction
        ) {
            return '';
        }

        $langAuctioneer = $this->getTranslator()->translateForRtb('BIDDERCLIENT_AUCTIONEER', $auction);
        $langQuantity = $this->getTranslator()->translateForRtb('BIDDERCLIENT_QUANTITY_RTB', $auction);
        $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale($lotItemId, $auction->Id);
        if (!Floating::gt($quantity, 0, $quantityScale)) {
            return '';
        }

        $lotAmountRenderer = $this->createLotAmountRendererFactory()->create($auction->AccountId);
        $quantityFormatted = $lotAmountRenderer->makeQuantity($quantity, $quantityScale);
        $output = '<span class="auc-lbl">' . $langAuctioneer . ':</span> '
            . '<span style="color:#FF0000;font-weight:bold;text-decoration:blink;">'
            . $langQuantity . " x " . $quantityFormatted . ' !!'
            . '</span><br />' . "\n";
        return $output;
    }
}
