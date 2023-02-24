<?php

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class EnableDecrement
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class EnableDecrement extends CommandBase implements RtbCommandHelperAwareInterface
{
    use LotRendererAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;

    protected bool $isEnabled = false;

    public function execute(): void
    {
        if (
            !$this->checkConsoleSync()
            || !$this->checkRunningLot()
        ) {
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        $rtbCurrent = $this->getRtbCurrent();

        $auctionLot = $this->getAuctionLot();
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $this->getLogger()->log("Admin clerk enable decrement on lot {$lotNo} ({$rtbCurrent->LotItemId})");

        $rtbCurrent->EnableDecrement = $this->isEnabled;
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
    }

    /**
     * @param bool $enabled
     * @return static
     */
    public function enableEnabled(bool $enabled): static
    {
        $this->isEnabled = $enabled;
        return $this;
    }
}
