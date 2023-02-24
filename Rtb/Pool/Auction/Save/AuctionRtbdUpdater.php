<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/4/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Auction\Save;

use Auction;
use AuctionRtbd;
use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Auction\Simultaneous\Load\SimultaneousAuctionLoaderAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Log\Support\SupportLoggerAwareTrait;
use Sam\Rtb\Pool\Auction\Link\AuctionRtbdLinkerCreateTrait;
use Sam\Rtb\Pool\Auction\Load\AuctionRtbdLoaderAwareTrait;
use Sam\Rtb\Pool\Auction\Validate\AuctionRtbdCheckerCreateTrait;
use Sam\Rtb\Pool\Config\RtbdPoolConfigManagerAwareTrait;
use Sam\Rtb\Pool\Discovery\Search\AuctionRtbdAdviser;
use Sam\Rtb\Pool\Instance\RtbdNameAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Class AuctionRtbdUpdater
 * @package
 * @method Auction getAuction()
 * @method AuctionRtbd getAuctionRtbd()
 */
class AuctionRtbdUpdater extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionRendererAwareTrait;
    use AuctionRtbdCheckerCreateTrait;
    use AuctionRtbdLinkerCreateTrait;
    use AuctionRtbdLoaderAwareTrait;
    use AuctionRtbdProducerAwareTrait;
    use EditorUserAwareTrait;
    use RtbdNameAwareTrait;
    use RtbdPoolConfigManagerAwareTrait;
    use SimultaneousAuctionLoaderAwareTrait;
    use SupportLoggerAwareTrait;

    private ?array $logData = null;
    private ?string $logMessage = null;
    private string $oldRtbdName = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Persist at rtbd side, if linked rtbd changed.
     * @return static
     */
    public function update(): static
    {
        $this->logData = $this->logMessage = null;
        $auctionRtbd = $this->getAuctionRtbdLoader()
            ->setEditorUser($this->getEditorUser())
            ->loadOrCreate($this->getAuctionId());
        $this->setAuctionRtbd($auctionRtbd);
        $this->setOldRtbdName($auctionRtbd->RtbdName);
        if ($this->oldRtbdName !== $this->getRtbdName()) { //  update only when changed
            $this->updateAuction();
            $this->updateSimultaneousAuction();
            $this->getSupportLogger()->trace($this->logMessage . composeSuffix($this->logData));
        }
        return $this;
    }

    /**
     * Search for rtbd instance should handle auction. Persist, if linked rtbd changed.
     * @return static
     */
    public function updateBySuggestedAndPersist(): static
    {
        $newRtbdName = AuctionRtbdAdviser::new()
            ->setAuction($this->getAuction())
            ->suggestName();
        $this->setRtbdName($newRtbdName);
        $this->update();
        return $this;
    }

    /**
     * @return array
     */
    public function getLogData(): array
    {
        return $this->logData;
    }

    /**
     * @return string
     */
    public function getOldRtbdName(): string
    {
        return $this->oldRtbdName;
    }

    /**
     * Store previous rtbd name for info purposes. For internal use only.
     * @param string $rtbdName
     * @return static
     */
    private function setOldRtbdName(string $rtbdName): static
    {
        $this->oldRtbdName = trim($rtbdName);
        return $this;
    }

    /**
     * Update subject auction
     */
    private function updateAuction(): void
    {
        $auction = $this->getAuction();
        $auctionRtbd = $this->getAuctionRtbd();
        $newRtbdName = $this->getRtbdName();
        $oldRtbdName = $auctionRtbd->RtbdName;
        $shouldUpdateInRtbd = $this->createAuctionRtbdChecker()->shouldUpdateInRtbd($oldRtbdName);
        $this->logMessage = 'Auction relation to rtbd pool instance updated';
        $this->logData = [
            'a' => $auction->Id,
            'new rtbd' => $newRtbdName,
            'old rtbd' => $oldRtbdName,
            'sale#' => $this->getAuctionRenderer()->renderSaleNo($auction),
            'acc' => $auction->AccountId,
        ];

        if ($shouldUpdateInRtbd) {
            // We update auction in rtbd state and persist inside rtbd process
            $auctionRtbdLinker = $this->createAuctionRtbdLinker()
                ->setAuction($auction)
                ->setOldRtbdName($oldRtbdName)
                ->setNewRtbdName($newRtbdName);
            $auctionRtbdLinker->link();
        } else {
            $this->getAuctionRtbdProducer()
                ->setEditorUserId($this->getEditorUserId())
                ->setRtbdName($newRtbdName)
                ->update($auctionRtbd);
        }
    }

    /**
     * Update simultaneous auction connected with subject auction
     */
    private function updateSimultaneousAuction(): void
    {
        $newRtbdName = $this->getRtbdName();
        $simultaneousAuction = $this->getSimultaneousAuctionLoader()->findSimultaneousAuction($this->getAuction());
        if ($simultaneousAuction) {
            $simultaneousAuctionRtbd = $this->getAuctionRtbdLoader()
                ->setEditorUser($this->getEditorUser())
                ->loadOrCreate($simultaneousAuction->Id);
            $oldSimultaneousRtbdName = $simultaneousAuctionRtbd->RtbdName;
            $this->logMessage .= ' and simultaneous auction updated too';
            $this->logData = array_merge(
                $this->logData,
                [
                    'simult.a' => $simultaneousAuction->Id,
                    'simult.new rtbd' => $newRtbdName,
                    'simult.old rtbd' => $oldSimultaneousRtbdName,
                    'simult.sale#' => $this->getAuctionRenderer()->renderSaleNo($simultaneousAuction),
                ]
            );
            $shouldUpdateSimultaneousInRtbd = $this->createAuctionRtbdChecker()->shouldUpdateInRtbd($oldSimultaneousRtbdName);
            if ($shouldUpdateSimultaneousInRtbd) {
                $auctionRtbdLinker = $this->createAuctionRtbdLinker()
                    ->setAuction($simultaneousAuction)
                    ->setOldRtbdName($oldSimultaneousRtbdName)
                    ->setNewRtbdName($newRtbdName);
                $auctionRtbdLinker->link();
            } else {
                $this->getAuctionRtbdProducer()
                    ->setEditorUserId($this->getEditorUserId())
                    ->setRtbdName($newRtbdName)
                    ->update($simultaneousAuctionRtbd);
            }
        }
    }
}
