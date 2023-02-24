<?php
/**
 * SAM-9365: Refactor BidIncrementCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\BidIncrement\Internal\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * This class contains increment data from a CSV row.
 *
 * Class RowInput
 * @package Sam\Import\Csv\BidIncrement\Internal\Dto
 */
class RowInput extends CustomizableClass
{
    /**
     * @var string
     */
    public string $amount;
    /**
     * @var string
     */
    public string $increment;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $amount, string $increment): static
    {
        $this->amount = $amount;
        $this->increment = $increment;
        return $this;
    }
}
