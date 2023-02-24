<?php
/**
 * SAM-9734: Fix email reminder behavior for the case when last run timestamps are missed
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Reminder\Common\Dto;

use DateTime;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ProcessingInput
 */
class ProcessingInput extends CustomizableClass
{
    /**
     * @var DateTime
     */
    public DateTime $currentDateUtc;
    /**
     * @var int
     */
    public int $scriptInterval;
    /**
     * @var DateTime|null
     */
    public ?DateTime $lastRunDateUtc;
    /**
     * @var int|null
     */
    public ?int $emailFrequency;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        DateTime $currentDateUtc,
        int $scriptInterval,
        ?DateTime $lastRunDateUtc,
        ?int $emailFrequency
    ): ProcessingInput {
        $this->currentDateUtc = $currentDateUtc;
        $this->scriptInterval = $scriptInterval;
        $this->lastRunDateUtc = $lastRunDateUtc;
        $this->emailFrequency = $emailFrequency;
        return $this;
    }
}
