<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Sam\Rtb\Hybrid\HelpersAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class StopAuction
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class StopAuction extends \Sam\Rtb\Command\Concrete\Base\StopAuction
{
    use HelpersAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     */
    public function execute(): void
    {
        parent::execute();

        $rtbCurrent = $this->getRtbCurrent();
        $rtbCurrent->LotEndDate = null;
        $rtbCurrent->PauseDate = null;
        $rtbCurrent->FairWarningSec = null;
        $rtbCurrent->LotItemId = null;
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
    }

    protected function createResponses(): void
    {
        parent::createResponses();
        $this->getResponseHelper()->applyAdditionalInfo($this);
    }
}
