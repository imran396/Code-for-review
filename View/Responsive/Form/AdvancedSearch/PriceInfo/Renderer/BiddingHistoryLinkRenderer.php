<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 15, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Renderer;

use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveBiddingHistoryUrlConfig;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\HtmlWrapRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslator;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslatorAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Load\AdvancedSearchLotDto;

/**
 * Class BiddingHistoryLinkRenderer
 */
class BiddingHistoryLinkRenderer extends CustomizableClass
{
    use CachedTranslatorAwareTrait;
    use HtmlWrapRendererCreateTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(CachedTranslator $cachedTranslator): static
    {
        $this->setCachedTranslator($cachedTranslator);
        return $this;
    }

    /**
     * Return html for "bidding history" link block
     * extended to use mobile links, transferred and adopted from from \Sam\Bidding\Qform\MobileCell
     * @param AdvancedSearchLotDto $dto
     * @return string
     */
    public function render(AdvancedSearchLotDto $dto): string
    {
        $output = '';
        $auctionId = $dto->auctionId;
        $lotItemId = $dto->lotItemId;
        $bidCount = $dto->bidCount;
        $currentBidId = $dto->currentBidId;

        // && $this->isAccessLotBiddingHistory //Anyway we always send bid count by auto-sync.
        if ($auctionId) {
            $url = $this->getUrlBuilder()->build(
                ResponsiveBiddingHistoryUrlConfig::new()->forWeb($lotItemId, $auctionId)
            );
            $auctionStatusPureChecker = AuctionStatusPureChecker::new();
            $output = $auctionStatusPureChecker->isTimed($dto->auctionType)
                ? $this->renderForTimed($bidCount, $url)
                : $this->renderForLiveAndHybrid($currentBidId, $url);
        }
        if ($output !== '') {
            $output = $this->createHtmlWrapRenderer()->withSpan($output, ['class' => 'bid-history']);
        }
        return $output;
    }

    /**
     * @param string $url
     * @param string $text
     * @return string
     */
    private function renderLink(string $url, string $text): string
    {
        $output = "<a href=\"{$url}\">{$text}</a>";
        return $output;
    }

    /**
     * @param int $bidCount
     * @param string $url
     * @return string
     */
    protected function renderForTimed(int $bidCount, string $url): string
    {
        $translateFieldKey = $bidCount === 1 ? 'GENERAL_BID' : 'GENERAL_BIDS';
        $bidText = $this->getCachedTranslator()->translate($translateFieldKey, 'general');
        $text = $this->getCachedTranslator()->translate('MYITEMS_BIDDINGHISTORY', 'myitems');
        $text .= "({$bidCount} {$bidText})";
        $output = $this->renderLink($url, $text);
        return $output;
    }

    /**
     * @param int $currentBidId - 0 - if nobody place a bid, then we don't render bidding history link
     * @param string $url
     * @return string
     */
    protected function renderForLiveAndHybrid(int $currentBidId, string $url): string
    {
        $output = '';
        if ($currentBidId) {
            $text = $this->getCachedTranslator()->translate('MYITEMS_BIDDINGHISTORY', 'myitems');
            $output = $this->renderLink($url, $text);
        }
        return $output;
    }
}
