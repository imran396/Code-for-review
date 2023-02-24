<?php
/**
 * SAM-4921: Clear message center and auction history at Rtb Admin Clerk and Auctioneer consoles.
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           23.03.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Log\LogCleaner;

/**
 * Class ClearLog
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class ClearLog extends CommandBase implements RtbCommandHelperAwareInterface
{
    use LotRendererAwareTrait;

    /** @var string[] file types to clean */
    protected array $types = [];

    /**
     */
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

        foreach ($this->getTypes() as $type) {
            LogCleaner::new()->clean($type, $this->getAuctionId());
        }

        $lotNo = $this->getLotRenderer()->renderLotNo($this->getAuctionLot());

        $this->getLogger()->log("Admin clerk clears lot {$lotNo} history and message center ({$rtbCurrent->LotItemId})");
    }

    protected function createResponses(): void
    {
        $this->setResponses([]);
    }

    /**
     * @return string[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @param string[] $types
     * @return ClearLog
     */
    public function setTypes(array $types): ClearLog
    {
        $this->types = ArrayCast::makeStringArray($types);
        return $this;
    }
}
