<?php
/**
 * SAM-10775: Create in Admin Web the "Tax Definition Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Definition\Edit\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxDefinitionRangeDto
 * @package Sam\Tax\StackedTax\Definition\Edit\Dto
 */
class TaxDefinitionRangeDto extends CustomizableClass
{
    public readonly string $amount;
    public readonly string $fixed;
    public readonly string $percent;
    public readonly string $mode;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        string $amount,
        string $fixed,
        string $percent,
        string $mode
    ): static {
        $this->amount = $amount;
        $this->fixed = $fixed;
        $this->percent = $percent;
        $this->mode = $mode;
        return $this;
    }
}
