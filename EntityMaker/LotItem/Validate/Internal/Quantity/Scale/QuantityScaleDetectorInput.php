<?php
/**
 * SAM-10432: Reverse auction > Allow buyer select quantity: Validation is displayed but no field is highlighted
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

namespace Sam\EntityMaker\LotItem\Validate\Internal\Quantity\Scale;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;

/**
 * Class QuantityScaleDetectorInput
 * @package Sam\EntityMaker\LotItem\Validate\Internal\Quantity\Scale
 */
class QuantityScaleDetectorInput extends CustomizableClass
{
    public readonly ?int $lotItemId;
    public readonly ?string $quantityDigits;
    public readonly ?array $categoryIds;
    public readonly ?array $categoryNames;
    public readonly int $serviceAccountId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        ?int $lotItemId,
        ?string $quantityDigits,
        ?array $categoryIds,
        ?array $categoryNames,
        int $serviceAccountId
    ): static {
        $this->lotItemId = $lotItemId;
        $this->quantityDigits = $quantityDigits;
        $this->categoryIds = $categoryIds;
        $this->categoryNames = $categoryNames;
        $this->serviceAccountId = $serviceAccountId;
        return $this;
    }

    public function fromMakerDto(LotItemMakerInputDto $inputDto, LotItemMakerConfigDto $configDto): static
    {
        return $this->construct(
            Cast::toInt($inputDto->id),
            $inputDto->quantityDigits,
            $inputDto->categoriesIds,
            $inputDto->categoriesNames,
            $configDto->serviceAccountId
        );
    }
}
