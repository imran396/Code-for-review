<?php
/**
 * SAM-8005: Allow decimals in quantity
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Internal\Validate;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerConfigDto;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputDto;

/**
 * Class QuantityValidationInput
 * @package Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Internal\Validate
 */
class QuantityValidationInput extends CustomizableClass
{
    public ?int $id;
    public ?int $lotItemId;
    public int $serviceAccountId;
    public int $systemAccountId;
    public ?string $quantity;
    public int $quantityScale;
    public ?array $categoryNames;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function fromMakerDto(AuctionLotMakerInputDto $inputDto, AuctionLotMakerConfigDto $configDto, int $quantityScale): static
    {
        $this->id = Cast::toInt($inputDto->id);
        $this->lotItemId = Cast::toInt($inputDto->lotItemId);
        $this->serviceAccountId = $configDto->serviceAccountId;
        $this->systemAccountId = $configDto->systemAccountId;
        $this->quantity = $inputDto->quantity;
        $this->quantityScale = $quantityScale;
        $this->categoryNames = $inputDto->categoriesNames;

        return $this;
    }

    public function logData(): array
    {
        return [
            'id' => $this->id,
            'lotItemId' => $this->lotItemId,
            'serviceAccountId' => $this->serviceAccountId,
            'systemAccountId' => $this->systemAccountId,
            'quantity' => $this->quantity,
            'quantityScale' => $this->quantityScale,
            'categoryNames' => $this->categoryNames,
        ];
    }

}
