<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Dto;

use Sam\Core\Dto\StringDto;

/**
 * This class represents consignor commission fee range input data
 *
 * Class ConsignorCommissionFeeRangeDto
 * @package Sam\Consignor\Commission\Edit
 *
 * @property string $amount
 * @property string $fixed
 * @property string $percent
 * @property string $mode
 */
class ConsignorCommissionFeeRangeDto extends StringDto
{
    protected array $availableFields = [
        'amount',
        'fixed',
        'percent',
        'mode',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $fields
     * @return static
     */
    public function fromArray(array $fields): static
    {
        $dto = self::new();
        foreach ($fields as $fieldName => $value) {
            $dto->{$fieldName} = $value;
        }
        return $dto;
    }
}
