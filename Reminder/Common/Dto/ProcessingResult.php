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
 * Class ProcessResult
 * @package Sam\Reminder\Concrete\Payment
 */
class ProcessingResult extends CustomizableClass
{
    public int $countRemindedUsers = 0;
    public int $countAuctions = 0;
    public bool $isProcessed = false;
    public ?DateTime $lastRunUtc = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function enableProcessed(bool $enable): static
    {
        $this->isProcessed = $enable;
        return $this;
    }
}
