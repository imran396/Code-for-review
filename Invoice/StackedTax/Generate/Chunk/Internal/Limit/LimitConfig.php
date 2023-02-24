<?php
/**
 * SAM-10948: Stacked Tax. Invoice Management pages. Prepare Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Chunk\Internal\Limit;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LimitConfig
 * @package Sam\Invoice\StackedTax\Generate\Chunk\Internal\Limit
 */
class LimitConfig extends CustomizableClass
{
    public ?int $memoryLimit = null;
    public int $execStartTime;  // 5
    /** @var int|null duration in sec */
    public ?int $execMaxDuration;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $execMaxDuration max execution duration or null if no limit
     * @param int|null $memoryLimit
     * @return $this
     */
    public function construct(?int $execMaxDuration, ?int $memoryLimit): static
    {
        $this->execStartTime = time();
        $this->execMaxDuration = $execMaxDuration;
        $this->memoryLimit = $memoryLimit;
        return $this;
    }

    public function getExecStartTime(): int
    {
        return $this->execStartTime;
    }

    public function getExecMaxDuration(): ?int
    {
        return $this->execMaxDuration;
    }

    public function getMemoryLimit(): ?int
    {
        return $this->memoryLimit;
    }
}
