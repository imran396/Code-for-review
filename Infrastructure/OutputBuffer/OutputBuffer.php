<?php
/**
 * SAM-10424: Wrapper for output buffering functions
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 10, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\OutputBuffer;

use Sam\Core\Service\CustomizableClass;

/**
 * Class OutputBuffer
 * @package Sam\Infrastructure\OutputBuffer
 */
class OutputBuffer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Start output buffering, if it isn't started yet.
     * @param callable|null $callback
     * @param int $chunkSize
     * @param int $flags
     * @return bool
     */
    public function initialStart(callable|null $callback = null, int $chunkSize = 0, int $flags = PHP_OUTPUT_HANDLER_STDFLAGS): bool
    {
        if (ob_get_level()) {
            return false; // Already started
        }

        return $this->start($callback, $chunkSize, $flags);
    }

    public function start(callable|null $callback = null, int $chunkSize = 0, int $flags = PHP_OUTPUT_HANDLER_STDFLAGS): bool
    {
        return ob_start($callback, $chunkSize, $flags);
    }

    public function completeEndFlush(): bool
    {
        $result = false;
        while (ob_get_level() > 0) {
            $result = ob_end_flush();
        }
        return $result;
    }

    public function endFlush(): bool
    {
        if (ob_get_level() > 0) {
            return ob_end_flush();
        }
        return false;
    }

    public function getClean(): string|false
    {
        return ob_get_clean();
    }

    public function clean(): bool
    {
        return ob_clean();
    }

    public function getContents(): string|false
    {
        return ob_get_contents();
    }

    public function getLength(): int|false
    {
        return ob_get_length();
    }

    public function getLevel(): int
    {
        return ob_get_level();
    }

    public function completeEndClean(): bool
    {
        $result = false;
        while ($this->countHandlers() || $this->getLevel()) {
            $result = $this->endClean();
        }
        return $result;
    }

    public function listHandlers(): array
    {
        return ob_list_handlers();
    }

    public function countHandlers(): int
    {
        return count($this->listHandlers());
    }

    public function endClean(): bool
    {
        return ob_end_clean();
    }
}
