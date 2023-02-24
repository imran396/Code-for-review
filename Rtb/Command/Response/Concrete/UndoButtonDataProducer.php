<?php
/**
 * SAM-6459: Rtbd response - lot data producers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Response\Concrete;

use Sam\Core\Service\CustomizableClass;
use RtbCurrent;
use Sam\Core\Constants;
use Sam\Rtb\State\History\HistoryServiceFactoryCreateTrait;

/**
 * Class UndoButtonDataProducer
 * @package Sam\Rtb\Command\Response\Concrete
 */
class UndoButtonDataProducer extends CustomizableClass
{
    use HistoryServiceFactoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return array = [
     *  Constants\Rtb::RES_UNDO_BUTTON_DATA => [
     *   'Cnt' => int,
     *   'Cmd' => string
     *  ]
     * ]
     */
    public function produceData(RtbCurrent $rtbCurrent): array
    {
        $undoButtonData = $this->createHistoryServiceFactory()
            ->createHelper($rtbCurrent)
            ->getUndoButtonData($rtbCurrent);
        $data[Constants\Rtb::RES_UNDO_BUTTON_DATA] = $undoButtonData;
        return $data;
    }
}
