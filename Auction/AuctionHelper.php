<?php
/**
 * Trait for Auction Helper
 *
 * SAM-5065: Auction helper
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 6, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction;

use Account;
use Auction;
use Sam\Auction\Available\AuctionAvailabilityCheckerFactory;
use Sam\Auction\Available\AuctionHybridAvailabilityChecker;
use Sam\Auction\Simultaneous\Load\SimultaneousAuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class AuctionHelper
 * @package Sam\Auction
 */
class AuctionHelper extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use SimultaneousAuctionLoaderAwareTrait;

    protected AuctionHybridAvailabilityChecker $hybridHelper;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->hybridHelper = AuctionAvailabilityCheckerFactory::new()
            ->create(Constants\Auction::HYBRID);
        return $this;
    }

    /**
     * Return available auction types for current account
     * @param int $accountId
     * @return array<Constants\Auction::TIMED|Constants\Auction::LIVE|Constants\Auction::HYBRID>
     */
    public function getAvailableTypes(int $accountId): array
    {
        $types = [Constants\Auction::TIMED, Constants\Auction::LIVE];
        if ($this->hybridHelper->isAvailableForAccountId($accountId)) {
            $types[] = Constants\Auction::HYBRID;
        }
        return $types;
    }

    /**
     * Return available auction types for current account
     * @param Account $account
     * @return array
     */
    public function getAvailableTypesByAccount(Account $account): array
    {
        $types = [Constants\Auction::TIMED, Constants\Auction::LIVE];
        if ($this->hybridHelper->isAvailable($account)) {
            $types[] = Constants\Auction::HYBRID;
        }
        return $types;
    }

    /**
     * @return string[]
     */
    public function getStreamDisplayNames(): array
    {
        return Constants\Auction::$streamDisplayNames
            + ($this->cfg()->get('core->vendor->bidpathStreaming->enabled') ? Constants\Auction::$streamDisplayCoded : []);
    }

    /**
     * @return string[]
     */
    public function getStreamDisplayValues(): array
    {
        $values = Constants\Auction::$streamDisplayValues;
        if ($this->cfg()->get('core->vendor->bidpathStreaming->enabled')) {
            $values = array_merge($values, Constants\Auction::$streamDisplayCodedValues);
        }
        return $values;
    }

    /**
     * @param int $accountId
     * @return bool
     */
    public function isHybridAuctionAvailable(int $accountId): bool
    {
        $is = in_array(Constants\Auction::HYBRID, $this->getAvailableTypes($accountId), true);
        return $is;
    }

    /**
     * @param Auction $auction
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isSimultaneousAuctionAvailable(Auction $auction, bool $isReadOnlyDb = false): bool
    {
        $is = false;
        if ($auction->Simultaneous) {
            $simultaneousAuctionId = $this->getSimultaneousAuctionLoader()
                ->findSimultaneousAuctionId($auction, $isReadOnlyDb);
            if ($simultaneousAuctionId) {
                $is = true;
            }
        }
        return $is;
    }
}
