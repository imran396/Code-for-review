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

namespace Sam\Import\Csv\BidIncrement\Internal\Process;

use Sam\Core\Service\CustomizableClass;

/**
 * This class contains result data of processing the bid increment.
 *
 * Class Result
 * @package Sam\Import\Csv\BidIncrement\Internal\Process
 */
class Result extends CustomizableClass
{
    /**
     * @var bool
     */
    public bool $isUpdated;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(bool $isUpdated): static
    {
        $this->isUpdated = $isUpdated;
        return $this;
    }

    public function updated(): static
    {
        return $this->construct(true);
    }

    public function created(): static
    {
        return $this->construct(false);
    }
}
