<?php
/**
 * SAM-5925: Allow to set installation timezone and User preferred timezone
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Timezone\Save;

/**
 * Trait TimezoneProducerCreateTrait
 * @package Sam\Timezone\Save
 */
trait TimezoneProducerCreateTrait
{
    protected ?TimezoneProducer $timezoneProducer = null;

    /**
     * @return TimezoneProducer
     */
    protected function createTimezoneProducer(): TimezoneProducer
    {
        return $this->timezoneProducer ?: TimezoneProducer::new();
    }

    /**
     * @param TimezoneProducer $timezoneProducer
     * @return static
     * @internal
     */
    public function setTimezoneProducer(TimezoneProducer $timezoneProducer): static
    {
        $this->timezoneProducer = $timezoneProducer;
        return $this;
    }
}
