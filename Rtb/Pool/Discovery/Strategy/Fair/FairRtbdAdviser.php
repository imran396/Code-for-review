<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 05, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Discovery\Strategy\Fair;

use Sam\Core\Service\CustomizableClass;
use Sam\Log\Support\SupportLoggerAwareTrait;
use Sam\Rtb\Pool\Discovery\Strategy\Manual\ManualRtbdListProducer;
use Sam\Rtb\Pool\Instance\RtbdDescriptor;
use Sam\Rtb\Pool\Instance\RtbdDescriptorsAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Class FairRtbdAdviser
 * @package Sam\Rtb\Pool\Discovery\Strategy\Fair
 */
class FairRtbdAdviser extends CustomizableClass
{
    use AuctionAwareTrait;
    use RtbdDescriptorsAwareTrait;
    use RtbdLoadingDatesRangeProviderCreateTrait;
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
     * @throws \Exception
     */
    public function suggest(): ?RtbdDescriptor
    {
        $descriptor = $this->findByName();
        if ($descriptor) {
            return $descriptor;
        }

        return $this->findLessLoadedRtbd();
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
     * @return RtbdDescriptor|null
     * @throws \Exception
     */
    private function findLessLoadedRtbd(): ?RtbdDescriptor
    {
        $acceptableRtbdList = $this->getAcceptableRtbdList();
        if ($acceptableRtbdList) {
            $lessLoadedRtbdName = $this->detectLessLoadedRtbdName($acceptableRtbdList);
            foreach ($acceptableRtbdList as $acceptableRtbd) {
                if ($acceptableRtbd->getName() === $lessLoadedRtbdName) {
                    $message = 'Rtbd instance descriptor found by fair strategy'
                        . composeSuffix(['name' => $acceptableRtbd->getName()]);
                    $this->getSupportLogger()->trace($message);
                    return $acceptableRtbd;
                }
            }
        }
        return null;
    }

    /**
     * @param array $acceptableRtbdList
     * @return string
     * @throws \Exception
     */
    private function detectLessLoadedRtbdName(array $acceptableRtbdList): string
    {
        $auctionDateRange = $this->getAuctionDateRange();
        $rtbdLoadingLevel = $this->calcRtbdListLoadingLevel($acceptableRtbdList, $auctionDateRange);
        asort($rtbdLoadingLevel);
        $sortedRtbdNames = array_keys($rtbdLoadingLevel);
        return reset($sortedRtbdNames);
    }

    /**
     * @param array|RtbdDescriptor[] $rtbdList
     * @param array $dateRange
     * @return array
     * @throws \Exception
     */
    private function calcRtbdListLoadingLevel(array $rtbdList, array $dateRange): array
    {
        $loadingLevel = [];
        foreach ($rtbdList as $rtbd) {
            $loadingLevel[$rtbd->getName()] = 0;
        }
        $loadingDatesRange = $this->createRtbdLoadingDatesRangeProvider()->getLoadingDatesRange($rtbdList);

        foreach ($loadingDatesRange as $rtbdName => $rtbdLoadingDatesRange) {
            foreach ($rtbdLoadingDatesRange as $range) {
                if ($this->hasDateRangeIntersection($dateRange, $range)) {
                    $loadingLevel[$rtbdName]++;
                }
            }
        }
        return $loadingLevel;
    }

    /**
     * @param array $range1
     * @param array $range2
     * @return bool
     */
    private function hasDateRangeIntersection(array $range1, array $range2): bool
    {
        return $range1[0] <= $range2[1] && $range1[1] >= $range2[0];
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getAuctionDateRange(): array
    {
        return [
            $this->getAuction()->StartClosingDate,
            $this->getAuction()->EndDate
        ];
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
