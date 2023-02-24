<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\AuctionLotItem\Internal;

use AuctionLotItem;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Log\Support\SupportLoggerAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;

/**
 * Class Logger
 * @package Sam\Observer\AuctionLotItem
 * @internal
 */
class LotStatusChangeLogger extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use LotRendererAwareTrait;
    use SupportLoggerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function onCreate(EntityObserverSubject $subject): void
    {
        $this->log($subject);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->log($subject);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isPropertyModified('LotStatusId');
    }

    protected function log(EntityObserverSubject $subject): void
    {
        /** @var AuctionLotItem $auctionLot */
        $auctionLot = $subject->getEntity();
        $oldStatus = $subject->getOldPropertyValue('LotStatusId');
        $oldStatusName = Constants\Lot::$lotStatusNames[$oldStatus] ?? 'n/a';
        $newStatus = $auctionLot->LotStatusId;
        $newStatusName = Constants\Lot::$lotStatusNames[$newStatus] ?? 'n/a';
        $message = "Lot status changed"
            . composeSuffix(
                [
                    'li' => $auctionLot->LotItemId,
                    'lot#' => $this->getLotRenderer()->renderLotNo($auctionLot),
                    'a' => $auctionLot->AuctionId,
                    'old' => $oldStatusName . " ({$oldStatus})",
                    'new' => $newStatusName . " ({$newStatus})",
                ]
            );
        $this->getSupportLogger()->debug($message, null, 8);
    }
}
