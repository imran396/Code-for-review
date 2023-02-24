<?php
/**
 * SAM-9809:  Refactor Action Queue Module
 * https://bidpath.atlassian.net/browse/SAM-9809
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\ActionQueue\Internal\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class ProcessingResult
 * @package Sam\ActionQueue
 */
class ProcessingResult extends CustomizableClass
{
    /**
     * @var int
     */
    public int $failedEvents = 0;
    /**
     * @var int
     */
    public int $failedEventsHigh = 0;
    /**
     * @var int
     */
    public int $failedEventsMedium = 0;
    /**
     * @var int
     */
    public int $failedEventsLow = 0;
    /**
     * @var int
     */
    public int $processedEvents = 0;
    /**
     * @var int
     */
    public int $processedEventsHigh = 0;
    /**
     * @var int
     */
    public int $processedEventsMedium = 0;
    /**
     * @var int
     */
    public int $processedEventsLow = 0;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
