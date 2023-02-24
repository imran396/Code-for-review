<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Dto;

use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;

/**
 * This class contains bid info and lot status data from a CSV row.
 *
 * Class RowInput
 */
class RowInput extends CustomizableClass
{
    public readonly LotNoParsed $lotNoParsed;
    public readonly string $generalNote;
    public readonly string $hammerPrice;
    public readonly string $internetBid;
    public readonly UserMakerInputDto $userInputDto;
    public readonly UserMakerConfigDto $userConfigDto;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        UserMakerInputDto $userInputDto,
        UserMakerConfigDto $userConfigDto,
        LotNoParsed $lotNoParsed,
        string $generalNote,
        string $hammerPrice,
        string $internetBid
    ): static {
        $this->lotNoParsed = $lotNoParsed;
        $this->generalNote = $generalNote;
        $this->hammerPrice = $hammerPrice;
        $this->internetBid = $internetBid;
        $this->userInputDto = $userInputDto;
        $this->userConfigDto = $userConfigDto;
        return $this;
    }
}
