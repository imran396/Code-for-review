<?php
/**
 * SAM-6481: Add bidding options on the admin - auction - lots - added lots table
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\PlaceBid\Timed\Internal;

/**
 * Trait EventLoggerCreateTrait
 * @package Sam\Application\Controller\Admin\PlaceBid\Timed\Internal
 * @internal
 */
trait EventLoggerCreateTrait
{
    protected ?EventLogger $eventLogger = null;

    /**
     * @return EventLogger
     */
    protected function createEventLogger(): EventLogger
    {
        return $this->eventLogger ?: EventLogger::new();
    }

    /**
     * @param EventLogger $eventLogger
     * @return static
     * @internal
     */
    public function setEventLogger(EventLogger $eventLogger): static
    {
        $this->eventLogger = $eventLogger;
        return $this;
    }
}
