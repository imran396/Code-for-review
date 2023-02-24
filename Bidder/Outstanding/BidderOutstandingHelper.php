<?php
/**
 * Helper methods for outstanding limit feature
 *
 * SAM-2710: Bidonfusion - Bidder spending reports and thresholds
 * https://bidpath.atlassian.net/browse/SAM-2710
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Mar 27, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\Outstanding;

use AuctionBidder;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class Helper
 * @package Sam\Bidder\Outstanding
 */
class BidderOutstandingHelper extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use CurrencyLoaderAwareTrait;
    use NumberFormatterAwareTrait;
    use TranslatorAwareTrait;
    use UserAwareTrait;

    /**
     * @var string
     */
    protected string $currencySign = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if outstanding limit exceeded
     * @param AuctionBidder $auctionBidder
     * @param float $currentBidAmount
     * @return bool
     */
    public function isLimitExceeded(AuctionBidder $auctionBidder, float $currentBidAmount = 0.): bool
    {
        $exceed = false;
        $outstanding = $auctionBidder->Spent - $auctionBidder->Collected + $currentBidAmount;
        $maxOutstanding = $this->getMaxOutstanding($auctionBidder);
        if ($maxOutstanding !== null) {
            $exceed = Floating::gt($outstanding, $maxOutstanding);
        }
        return $exceed;
    }

    /**
     * Return max outstanding limit for current bidder in auction
     * @param AuctionBidder $auctionBidder
     * @return float|null
     */
    public function getMaxOutstanding(AuctionBidder $auctionBidder): ?float
    {
        $auction = $this->getAuctionLoader()->load($auctionBidder->AuctionId, true);
        if (!$auction) {
            log_error(
                "Available auction not found, when detecting max outstanding"
                . composeSuffix(['a' => $auctionBidder->AuctionId, 'aub' => $auctionBidder->Id])
            );
            return null;
        }
        $maxOutstanding = $auction->MaxOutstanding;
        $this->setUserId($auctionBidder->UserId);
        $userInfo = $this->getUserInfo();
        if (
            $userInfo
            && $userInfo->MaxOutstanding !== null
        ) {
            $maxOutstanding = $userInfo->MaxOutstanding;
        }
        return $maxOutstanding;
    }

    /**
     * Return text of outstanding limit exceeded message
     * @param AuctionBidder $auctionBidder
     * @return string
     */
    public function getOutstandingLimitExceededText(AuctionBidder $auctionBidder): string
    {
        $text = $this->getTranslator()->translate('GENERAL_OUTSTANDING_LIMIT_EXCEEDED_MAX_AMOUNT', 'general');
        $maxOutstanding = $this->getMaxOutstanding($auctionBidder);
        $currencySign = $this->getCurrencySign($auctionBidder);
        $maxOutstandingFormatted = $currencySign . $this->getNumberFormatter()->formatMoney($maxOutstanding);
        $text = sprintf($text, $maxOutstandingFormatted);
        return $text;
    }

    /**
     * Get currency sign
     * @param AuctionBidder $auctionBidder
     * @return string
     */
    public function getCurrencySign(AuctionBidder $auctionBidder): string
    {
        if (!$this->currencySign) {
            $this->currencySign = $this->getCurrencyLoader()->detectDefaultSign($auctionBidder->AuctionId);
        }
        return $this->currencySign;
    }

    /**
     * Set currency sign
     * @param string $currencySign
     * @return static
     */
    public function setCurrencySign(string $currencySign): static
    {
        $this->currencySign = trim($currencySign);
        return $this;
    }
}
