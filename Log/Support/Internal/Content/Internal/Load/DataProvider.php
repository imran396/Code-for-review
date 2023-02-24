<?php
/**
 * SAM-9561: Refactor support logger
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Support\Internal\Content\Internal\Load;

use DateTimeImmutable;
use DateTimeInterface;
use Sam\Application\Process\ApplicationProcessGuidManager;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\Log\Support\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function detectProcessGuid(): string
    {
        return ApplicationProcessGuidManager::new()->getProcessGuid();
    }

    public function detectDebugBacktrace(int $deep): array
    {
        return debug_backtrace(limit: $deep + 1);
    }

    public function detectMemoryUsage(): int
    {
        return memory_get_usage(true);
    }

    public function detectBasename(string $file): string
    {
        return basename($file);
    }

    public function detectCurrentDateUtc(): DateTimeInterface
    {
        return new DateTimeImmutable('now');
    }
}
