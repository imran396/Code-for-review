<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Convert;

use Sam\Consignor\Commission\Edit\Dto\ConsignorCommissionFeeRangeDto;
use Sam\EntityMaker\Base\Data\Range;
use Sam\Core\Service\CustomizableClass;

/**
 * The main purpose of this class is to map data between EntityMaker Range dto and ConsignorCommissionFeeRangeDto
 *
 * Class ConsignorCommissionFeeRangeDtoConverter
 * @package Sam\Consignor\Commission\Convert
 */
class ConsignorCommissionFeeRangeDtoConverter extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Map list of ConsignorCommissionFeeRangeDto to EntityMaker Range dto.
     * Using to fill entity maker dto with data produced by consignor commission fee edit form
     *
     * @param ConsignorCommissionFeeRangeDto[] $consignorCommissionFeeRangeDtos
     * @return Range[]|null
     */
    public function toRanges(?array $consignorCommissionFeeRangeDtos): ?array
    {
        if (!$consignorCommissionFeeRangeDtos) {
            return $consignorCommissionFeeRangeDtos;
        }
        $convertedRanges = array_map(
            static function (ConsignorCommissionFeeRangeDto $dto) {
                return Range::fromArray($dto->toArray());
            },
            $consignorCommissionFeeRangeDtos
        );
        return $convertedRanges;
    }

    /**
     * Map list of EntityMaker Range dto to list of ConsignorCommissionFeeRangeDto.
     * Using in EntityMakers for validating and saving ranges by ConsignorCommissionFeeRange Validator and Producer
     *
     * @param Range[] $ranges
     * @return ConsignorCommissionFeeRangeDto[]
     */
    public function fromRanges(array $ranges): array
    {
        $convertedRanges = array_map(
            static function (Range $range) {
                return ConsignorCommissionFeeRangeDto::new()->fromArray($range->toArray());
            },
            $ranges
        );
        return $convertedRanges;
    }
}
