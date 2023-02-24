<?php
/**
 * SAM-8005: Allow decimals in quantity
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 13, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Scale;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerConfigDto;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputDto;

/**
 * Class QuantityScaleDetectorInput
 * @package Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Scale
 */
class QuantityScaleDetectorInput extends CustomizableClass
{
    public readonly ?int $auctionLotId;
    public readonly ?int $lotItemId;
    public readonly ?string $quantityDigits;
    public readonly ?array $categoryNames;
    public readonly int $entityAccountId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        ?int $auctionLotId,
        ?int $lotItemId,
        ?string $quantityDigits,
        ?array $categoryNames,
        int $entityAccountId
    ): static {
        $this->auctionLotId = $auctionLotId;
        $this->lotItemId = $lotItemId;
        $this->quantityDigits = $quantityDigits;
        $this->categoryNames = $categoryNames;
        $this->entityAccountId = $entityAccountId;
        return $this;
    }

    public function fromMakerDto(AuctionLotMakerInputDto $inputDto, AuctionLotMakerConfigDto $configDto): static
    {
        return $this->construct(
            Cast::toInt($inputDto->id),
            Cast::toInt($inputDto->lotItemId),
            $inputDto->quantityDigits,
            $inputDto->categoriesNames,
            $configDto->serviceAccountId
        );
    }
}
