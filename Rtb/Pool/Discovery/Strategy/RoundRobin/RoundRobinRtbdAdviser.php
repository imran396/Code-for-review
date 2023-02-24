<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 01, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Discovery\Strategy\RoundRobin;

use Sam\Core\Service\CustomizableClass;
use Sam\Log\Support\SupportLoggerAwareTrait;
use Sam\Rtb\Pool\Discovery\Strategy\Manual\ManualRtbdListProducer;
use Sam\Rtb\Pool\Discovery\Strategy\RoundRobin\Load\LinkedRtbdLoaderCreateTrait;
use Sam\Rtb\Pool\Instance\RtbdDescriptor;
use Sam\Rtb\Pool\Instance\RtbdDescriptorsAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Class RoundRobinRtbdAdviser
 * @package Sam\Rtb\Pool\Discovery\Strategy\RoundRobin
 */
class RoundRobinRtbdAdviser extends CustomizableClass
{
    use AuctionAwareTrait;
    use LinkedRtbdLoaderCreateTrait;
    use RtbdDescriptorsAwareTrait;
    use SupportLoggerAwareTrait;

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
        $descriptor = $this->findByName();
        if ($descriptor) {
            return $descriptor;
        }

        return $this->findRoundRobin();
    }

    /**
     * @return RtbdDescriptor|null
     */
    private function findRoundRobin(): ?RtbdDescriptor
    {
        $acceptableRtbdDescriptors = $this->getAcceptableRtbdList();
        $acceptableRtbdNames = array_map(
            static function (RtbdDescriptor $descriptor) {
                return $descriptor->getName();
            },
            $acceptableRtbdDescriptors
        );

        $lastLinkedRtbd = $this->createLinkedRtbdLoader()->loadLastLinkedRtbdName($acceptableRtbdNames);
        $nextRtbdIndex = $this->getNextRtbdListIndex($acceptableRtbdNames, $lastLinkedRtbd);
        return $acceptableRtbdDescriptors[$nextRtbdIndex] ?? null;
    }

    /**
     * @param string[] $rtbdNames
     * @param string|null $lastLinkedRtbdName
     * @return int
     */
    private function getNextRtbdListIndex(array $rtbdNames, ?string $lastLinkedRtbdName = null): int
    {
        if ($lastLinkedRtbdName === null) {
            return 0;
        }
        $lastLinkedRtbdIndex = array_search($lastLinkedRtbdName, $rtbdNames, true);
        return ($lastLinkedRtbdIndex + 1) % count($rtbdNames);
    }

    /**
     * Find by name
     * @return RtbdDescriptor|null
     */
    private function findByName(): ?RtbdDescriptor
    {
        $descriptors = $this->getRtbdDescriptors();
        $name = $this->getAuctionRtbdOrCreate()->RtbdName;
        if ($name !== '') {
            foreach ($descriptors as $descriptor) {
                if (
                    $descriptor->isValid()
                    && $descriptor->getName() === $name
                ) {
                    $message = 'Rtbd instance descriptor found by name'
                        . composeSuffix(['name' => $descriptor->getName()]);
                    $this->getSupportLogger()->trace($message);
                    return $descriptor;
                }
            }
        }
        return null;
    }

    /**
     * @return RtbdDescriptor[]
     */
    private function getAcceptableRtbdList(): array
    {
        return ManualRtbdListProducer::new()
            ->setAuctionId($this->getAuctionId())
            ->makePrioritizedList();
    }
}
