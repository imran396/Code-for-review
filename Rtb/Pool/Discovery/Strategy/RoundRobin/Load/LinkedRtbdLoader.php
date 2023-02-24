<?php
/**
 * SAM-3924: RTBD scaling by providing a "repeater/ broadcasting" service for viewers
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

namespace Sam\Rtb\Pool\Discovery\Strategy\RoundRobin\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionRtbd\AuctionRtbdReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionRtbd\AuctionRtbdReadRepositoryCreateTrait;

/**
 * Class AuctionRtbdLoader
 * @package Sam\Rtb\Pool\Discovery\Strategy\RoundRobin\Load
 */
class LinkedRtbdLoader extends CustomizableClass
{
    use AuctionRtbdReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string[] $rtbdNameRange
     * @return string|null
     */
    public function loadLastLinkedRtbdName(array $rtbdNameRange): ?string
    {
        $row = $this->prepareRepository($rtbdNameRange)->loadRow();
        return $row['rtbdName'] ?? null;
    }

    /**
     * @param string[] $rtbdNames
     * @return AuctionRtbdReadRepository
     */
    private function prepareRepository(array $rtbdNames): AuctionRtbdReadRepository
    {
        return $this->createAuctionRtbdReadRepository()
            ->select(['artbd.rtbd_name as rtbdName'])
            ->filterRtbdName($rtbdNames)
            ->order('artbd.modified_on', false);
    }
}
