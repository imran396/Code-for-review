<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\AuctionLot\Internal\Dto;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerConfigDto;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\Import\Csv\Lot\Internal\UpdatingEntity\Common\LotItemIdDetectionResult;

/**
 * This class contains prepared auction lot and lot item data from a CSV row
 *
 * Class Row
 * @package Sam\Import\Csv\Lot\AuctionLot\Internal\Dto
 */
class Row extends CustomizableClass
{
    /**
     * @var LotItemMakerInputDto
     */
    public LotItemMakerInputDto $lotItemInputDto;
    /**
     * @var LotItemMakerConfigDto
     */
    public LotItemMakerConfigDto $lotItemConfigDto;
    /**
     * @var AuctionLotMakerInputDto
     */
    public AuctionLotMakerInputDto $auctionLotInputDto;
    /**
     * @var AuctionLotMakerConfigDto
     */
    public AuctionLotMakerConfigDto $auctionLotConfigDto;
    /**
     * @var LotItemIdDetectionResult
     */
    public LotItemIdDetectionResult $lotItemIdDetectionResult;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        LotItemMakerInputDto $lotItemInputDto,
        LotItemMakerConfigDto $lotItemConfigDto,
        AuctionLotMakerInputDto $auctionLotInputDto,
        AuctionLotMakerConfigDto $auctionLotConfigDto,
        LotItemIdDetectionResult $lotItemIdDetectionResult
    ): static {
        $this->auctionLotConfigDto = $auctionLotConfigDto;
        $this->auctionLotInputDto = $auctionLotInputDto;
        $this->lotItemConfigDto = $lotItemConfigDto;
        $this->lotItemIdDetectionResult = $lotItemIdDetectionResult;
        $this->lotItemInputDto = $lotItemInputDto;
        return $this;
    }
}
