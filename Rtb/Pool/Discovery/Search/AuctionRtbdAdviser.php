<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/27/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Discovery\Search;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Rtb\Pool\Config\RtbdPoolConfigManagerAwareTrait;
use Sam\Rtb\Pool\Discovery\Strategy\Fair\FairRtbdAdviser;
use Sam\Rtb\Pool\Discovery\Strategy\Manual\ManualRtbdAdviser;
use Sam\Rtb\Pool\Discovery\Strategy\RoundRobin\RoundRobinRtbdAdviser;
use Sam\Rtb\Pool\Instance\RtbdDescriptor;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Class RtbdAdviser
 * @package
 */
class AuctionRtbdAdviser extends CustomizableClass
{
    use AuctionAwareTrait;
    use RtbdPoolConfigManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return RtbdDescriptor|null
     */
    public function suggest(): ?RtbdDescriptor
    {
        $selectedDescriptor = $this->createConcreteStrategyAdviser()
            ->setAuction($this->getAuction())
            ->suggest();
        return $selectedDescriptor;
    }

    /**
     * @return string
     */
    public function suggestName(): string
    {
        $descriptor = $this->suggest();
        return $descriptor ? $descriptor->getName() : '';
    }

    /**
     * @return ManualRtbdAdviser|RoundRobinRtbdAdviser|FairRtbdAdviser
     */
    private function createConcreteStrategyAdviser(): ManualRtbdAdviser|RoundRobinRtbdAdviser|FairRtbdAdviser
    {
        $discoveryStrategy = $this->getRtbdPoolConfigManager()->getDiscoveryStrategy();
        return match ($discoveryStrategy) {
            Constants\RtbdPool::DS_ROUND_ROBIN => RoundRobinRtbdAdviser::new(),
            Constants\RtbdPool::DS_FAIR => FairRtbdAdviser::new(),
            default => ManualRtbdAdviser::new(),
        };
    }
}
