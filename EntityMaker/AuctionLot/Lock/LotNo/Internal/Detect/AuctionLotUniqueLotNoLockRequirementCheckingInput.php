<?php
/**
 * SAM-10802: Supply uniqueness of auction lot fields: lot#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Lock\LotNo\Internal\Detect;

use Sam\Core\Service\CustomizableClass;

/**
 * @package Sam\EntityMaker\AuctionLot
 */
class AuctionLotUniqueLotNoLockRequirementCheckingInput extends CustomizableClass
{
    public ?int $auctionLotId;
    public bool $isSetLotFullNum;
    public bool $isSetLotNum;
    public bool $isSetLotNumExtension;
    public bool $isSetLotNumPrefix;
    public string $lotFullNum;
    public string $lotNum;
    public string $lotNumExtension;
    public string $lotNumPrefix;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    public function construct(
        ?int $auctionLotId,
        string $lotNum,
        string $lotNumExtension,
        string $lotNumPrefix,
        string $lotFullNum,
        bool $isSetLotNum,
        bool $isSetLotNumExtension,
        bool $isSetLotNumPrefix,
        bool $isSetLotFullNum
    ): static {
        $this->auctionLotId = $auctionLotId;
        $this->lotNum = $lotNum;
        $this->lotNumExtension = $lotNumExtension;
        $this->lotNumPrefix = $lotNumPrefix;
        $this->lotFullNum = $lotFullNum;
        $this->isSetLotNum = $isSetLotNum;
        $this->isSetLotNumExtension = $isSetLotNumExtension;
        $this->isSetLotNumPrefix = $isSetLotNumPrefix;
        $this->isSetLotFullNum = $isSetLotFullNum;
        return $this;
    }

    public function logData(): array
    {
        $logData['ali'] = $this->auctionLotId;
        if ($this->isSetLotNum) {
            $logData['lot num'] = $this->lotNum;
        }
        if ($this->isSetLotNumExtension) {
            $logData['lot num ext'] = $this->lotNumExtension;
        }
        if ($this->isSetLotNumPrefix) {
            $logData['lot num prefix'] = $this->lotNumPrefix;
        }
        if ($this->isSetLotFullNum) {
            $logData['full lot#'] = $this->lotFullNum;
        }
        return $logData;
    }
}
