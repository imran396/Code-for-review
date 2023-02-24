<?php
/**
 * Update command handler, it refreshes auction bindings to rtbd instances in pool
 *
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/7/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Cli\Command\Link;

use Sam\Core\Cli\HandlerBase;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Rtb\Pool\Auction\Link\AuctionRtbdLinkerCreateTrait;
use Sam\Rtb\Pool\Auction\Save\AuctionRtbdUpdaterAwareTrait;
use Sam\Rtb\Pool\Config\RtbdPoolConfigManagerAwareTrait;
use Sam\Rtb\Pool\Instance\RtbdNameAwareTrait;

/**
 * Class LinkHandler
 * @package Sam\Rtb\Pool\Cli\Command\Update
 */
class LinkHandler extends HandlerBase
{
    use AuctionRtbdLinkerCreateTrait;
    use AuctionRtbdUpdaterAwareTrait;
    use EditorUserAwareTrait;
    use RtbdNameAwareTrait;
    use RtbdPoolConfigManagerAwareTrait;

    /**
     * @var int[]
     */
    private array $auctionIds = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function handle(): void
    {
        $newRtbdName = $this->getRtbdName();
        foreach ($this->getAuctionIds() as $auctionId) {
            $updater = $this->getAuctionRtbdUpdater()
                ->setAuctionId($auctionId)
                ->setEditorUser($this->getEditorUser())
                ->setRtbdName($newRtbdName)
                ->update();
            $oldRtbdName = $updater->getOldRtbdName();
            if ($oldRtbdName === $newRtbdName) {
                $this->output(
                    'Auction already linked to this rtbd instance'
                    . composeSuffix(['a' => $auctionId, 'rtbd' => $newRtbdName])
                );
                return;
            }
            $this->output('Auction updated' . composeSuffix($updater->getLogData()));
        }
    }

    /**
     * @return int[]
     */
    public function getAuctionIds(): array
    {
        return $this->auctionIds;
    }

    /**
     * @param int[] $auctionIds
     * @return static
     */
    public function setAuctionIds(array $auctionIds): static
    {
        $this->auctionIds = ArrayCast::makeIntArray($auctionIds, Constants\Type::F_INT_POSITIVE);
        return $this;
    }
}
