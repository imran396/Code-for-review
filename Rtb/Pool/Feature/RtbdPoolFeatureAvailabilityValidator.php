<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/2/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Feature;

use Auction;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Rtb\Pool\Config\RtbdPoolConfigManagerAwareTrait;

/**
 * Class RtbdPoolFeatureHelper
 * @package Sam\Rtb\Pool\Feature
 */
class RtbdPoolFeatureAvailabilityValidator extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;
    use RtbdPoolConfigManagerAwareTrait;

    public const ERR_FEATURE_DISABLED = 1;
    public const ERR_DISCOVERY_STRATEGY_INVALID = 2;
    public const ERR_DESCRIPTORS_ABSENT = 3;
    public const ERR_AUCTION_TYPE_INCORRECT = 4;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * To initialize instance properties
     * @return static
     */
    public function initInstance(): static
    {
        // Init ResultStatusCollector
        $errorMessages = [
            self::ERR_FEATURE_DISABLED => 'Feature disabled in installation config',
            self::ERR_DISCOVERY_STRATEGY_INVALID => 'Rtbd instance discovery strategy invalid',
            self::ERR_DESCRIPTORS_ABSENT => 'Valid rtbd instance descriptors not found',
            self::ERR_AUCTION_TYPE_INCORRECT => 'Wrong auction type for rtbd pool feature',
        ];
        $this->getResultStatusCollector()->initAllErrors($errorMessages);
        return $this;
    }

    /**
     * @param Auction $auction
     * @return bool
     */
    public function isAvailableForAuction(Auction $auction): bool
    {
        return $this->isAvailableByAuctionType($auction->AuctionType);
    }

    /**
     * @param string $auctionType
     * @return bool
     */
    public function isAvailableByAuctionType(string $auctionType): bool
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if (!$auctionStatusPureChecker->isLiveOrHybrid($auctionType)) {
            $this->getResultStatusCollector()->addError(self::ERR_AUCTION_TYPE_INCORRECT);
            return false;
        }
        return $this->isAvailable();
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        $isAvailable = true;
        $configManager = $this->getRtbdPoolConfigManager();
        if (!$this->isEnabled()) {
            $this->getResultStatusCollector()->addError(self::ERR_FEATURE_DISABLED);
            $isAvailable = false;
        }
        if ($configManager->getDiscoveryStrategy() === null) {
            $this->getResultStatusCollector()->addError(self::ERR_DISCOVERY_STRATEGY_INVALID);
            $isAvailable = false;
        }
        if (count($configManager->getValidDescriptors()) === 0) {
            $this->getResultStatusCollector()->addError(self::ERR_DESCRIPTORS_ABSENT);
            $isAvailable = false;
        }
        return $isAvailable;
    }

    /**
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->getRtbdPoolConfigManager()->isEnabled();
    }
}
