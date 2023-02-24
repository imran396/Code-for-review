<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/6/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Auction\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\Pool\Config\RtbdPoolConfigManagerAwareTrait;
use Sam\Rtb\Pool\Instance\RtbdDescriptor;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\Session\RtbSessionManagerCreateTrait;

/**
 * Class AuctionRtbdChecker
 * @package
 */
class AuctionRtbdChecker extends CustomizableClass
{
    use RtbdPoolConfigManagerAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RtbSessionManagerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @return int
     */
    public function countActiveSessions(int $auctionId): int
    {
        $rtbSessionCount = $this->createRtbSessionManager()->count($auctionId);
        return $rtbSessionCount;
    }

    /**
     * TODO: remove or implement
     * Check, that linked to auction rtbd instance can be changed.
     * @param int $auctionId
     * @return bool
     */
    public function canChangeInstance(int $auctionId): bool
    {
        return true;
        // return $this->countActiveSessions($auctionId) === 0;
    }


    /**
     * Checks, if we need to send update rtbd state command
     * @param string|null $oldRtbdName
     * @return bool
     */
    public function shouldUpdateInRtbd(?string $oldRtbdName): bool
    {
        // If old rtbd name does not correspond actual instances,
        // this means, it cannot be actual and running, and we don't need to update rtbd state.
        // P.s. this condition has more sense, than $oldRtbdName !== null. Instance name is mandatory.
        $should = false;
        if ($this->isCorrectLink($oldRtbdName)) {
            $descriptor = $this->getRtbdPoolConfigManager()->getDescriptorByName($oldRtbdName);
            // Rtbd server should be running
            $should = $descriptor && $this->isRtbdRunning($descriptor);
        }
        return $should;
    }

    /**
     * Checks, if auction-to-rtbd correctly linked
     * @param string|null $rtbdName
     * @return bool
     */
    public function isCorrectLink(?string $rtbdName): bool
    {
        $rtbdNames = $this->getRtbdPoolConfigManager()->getRtbdNames();
        $is = (string)$rtbdName !== ''
            && in_array($rtbdName, $rtbdNames, true);
        return $is;
    }

    /**
     * Check rtbd instance from pool is started and running
     * @param RtbdDescriptor $descriptor
     * @return bool
     */
    public function isRtbdRunning(RtbdDescriptor $descriptor): bool
    {
        return $this->getRtbGeneralHelper()->isRtbdRunning($descriptor->getBindHost(), $descriptor->getBindPort());
    }
}
