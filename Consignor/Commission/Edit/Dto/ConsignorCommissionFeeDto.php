<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Dto;

use Sam\Core\Dto\StringDto;

/**
 * This class represents consignor commission fee input data
 *
 * Class ConsignorCommissionFeeDto
 * @package Sam\Consignor\Commission\Edit\Dto
 *
 * @property string $name
 * @property string $calculationMethod
 * @property string $feeReference
 * @property string $relatedEntityId
 */
class ConsignorCommissionFeeDto extends StringDto
{
    protected array $availableFields = [
        'name',
        'calculationMethod',
        'feeReference',
        'relatedEntityId',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
