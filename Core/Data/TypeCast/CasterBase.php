<?php
/**
 * Parent base class for type cast helpers
 *
 * SAM-4825: Strict type related adjustments
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/7/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Data\TypeCast;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Log\Support\SupportLogger;
use Sam\Log\Support\SupportLoggerAwareTrait;

/**
 * Base abstract parent class for concrete type casters
 * @package Sam\Core\Data\TypeCast
 */
abstract class CasterBase extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use SupportLoggerAwareTrait;

    protected ?string $defaultFilter = null;
    protected array $acceptableFilters = [];

    /**
     * @param string $filter
     * @return static
     */
    public function setDefaultFilter(string $filter): static
    {
        $this->defaultFilter = $filter;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDefaultFilter(): ?string
    {
        return $this->defaultFilter;
    }

    /**
     * Check correct filter argument was passed
     * @param string $filter
     * @return bool
     */
    protected function validateFilterArgument(string $filter): bool
    {
        $isValid = in_array($filter, $this->acceptableFilters, true);
        return $isValid;
    }

    /**
     * @param string $message
     */
    protected function log(string $message): void
    {
        if ($this->cfg()->get('core->general->typeCast->log->enabled')) {
            $this->getSupportLogger()
                ->log(
                    (int)$this->cfg()->get('core->general->typeCast->log->level'),
                    $message,
                    0,
                    [
                        SupportLogger::OP_IS_BACKTRACE => (bool)$this->cfg()->get('core->general->typeCast->log->backTrace->enabled'),
                        SupportLogger::OP_BACKTRACE_LINE_COUNT => $this->cfg()->get('core->general->typeCast->log->backTrace->lineCount'),
                    ]
                );
        }
    }
}
