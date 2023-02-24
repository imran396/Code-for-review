<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Dto;

use Sam\Core\Dto\StringDto;
use Sam\EntityMaker\Base\Data\Range;
use Sam\EntityMaker\Base\Dto\InputDto;

/**
 * This class represents consignor commission fee rule input data that is bounded to a lot, auction, or user entity
 *
 * Class ConsignorCommissionFeeRelatedEntityDto
 * @package Sam\Consignor\Commission\Edit\Dto
 *
 * @property string|int $id
 * @property ConsignorCommissionFeeRangeDto[] $ranges
 * @property string|int $calculationMethod
 * @property string $feeReference
 */
class ConsignorCommissionFeeRelatedEntityDto extends StringDto
{
    protected array $availableFields = [
        'id',
        'ranges',
        'calculationMethod',
        'feeReference',
    ];

    /**
     * We trim() input by default
     * @var string[]
     */
    protected array $noTrimFields = ['ranges'];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param InputDto $inputDto
     * @param string $idFiled
     * @param string $rangesField
     * @param string $calculationMethodField
     * @param string|null $feeReferenceField
     * @return static
     */
    public function fromEntityMakerInputDto(
        InputDto $inputDto,
        string $idFiled,
        string $rangesField,
        string $calculationMethodField,
        ?string $feeReferenceField = null
    ): static {
        $dto = self::new();
        if (isset($inputDto->$idFiled)) {
            $dto->id = $inputDto->$idFiled;
        }
        if (isset($inputDto->$rangesField)) {
            $ranges = $inputDto->$rangesField;
            if ($ranges) {
                $dto->ranges = array_map(
                    static function (Range $range) {
                        return ConsignorCommissionFeeRangeDto::new()->fromArray($range->toArray());
                    },
                    $inputDto->$rangesField
                );
            } else {
                $dto->ranges = [];
            }
        }
        if (isset($inputDto->$calculationMethodField)) {
            $dto->calculationMethod = $inputDto->$calculationMethodField;
        }
        if (
            $feeReferenceField
            && isset($inputDto->$feeReferenceField)
        ) {
            $dto->feeReference = $inputDto->$feeReferenceField;
        }
        return $dto;
    }
}
