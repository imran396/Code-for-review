<?php

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class ChangeDefaultIncrement
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class ChangeDefaultIncrement extends CommandBase implements RtbCommandHelperAwareInterface
{
    use LotRendererAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;

    protected ?float $defaultIncrement = null;

    /**
     * @param float|null $defaultIncrement
     * @return static
     */
    public function setDefaultIncrement(?float $defaultIncrement): static
    {
        $this->defaultIncrement = $defaultIncrement;
        return $this;
    }

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
        $this->getLogger()->log("Admin clerk changes default increment to {$this->defaultIncrement} on lot {$lotNo} ({$rtbCurrent->LotItemId})");

        $rtbCurrent->DefaultIncrement = $this->defaultIncrement;
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
    }
}
