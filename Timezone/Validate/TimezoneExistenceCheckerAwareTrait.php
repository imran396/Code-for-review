<?php
/**
 * Trait for TimezoneExistenceChecker
 *
 * SAM-4022: TimezoneLoader and TimezoneExistenceChecker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 15, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Timezone\Validate;

/**
 * Trait TimezoneExistenceCheckerAwareTrait
 */
trait TimezoneExistenceCheckerAwareTrait
{
    protected ?TimezoneExistenceChecker $timezoneExistenceChecker = null;

    /**
     * @return TimezoneExistenceChecker
     */
    protected function getTimezoneExistenceChecker(): TimezoneExistenceChecker
    {
        if ($this->timezoneExistenceChecker === null) {
            $this->timezoneExistenceChecker = TimezoneExistenceChecker::new();
        }
        return $this->timezoneExistenceChecker;
    }

    /**
     * @param TimezoneExistenceChecker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setTimezoneExistenceChecker(TimezoneExistenceChecker $timezoneExistenceChecker): static
    {
        $this->timezoneExistenceChecker = $timezoneExistenceChecker;
        return $this;
    }
}
