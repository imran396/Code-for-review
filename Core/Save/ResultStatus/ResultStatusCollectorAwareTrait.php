<?php
/**
 * SAM-4729: General logic for editor services
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/19/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Save\ResultStatus;

/**
 * Trait ResultStatusCollectorAwareTrait
 * @package Sam\Core\Save\ResultStatus
 */
trait ResultStatusCollectorAwareTrait
{
    /**
     * @var ResultStatusCollector|null
     */
    protected ?ResultStatusCollector $resultStatusCollector = null;

    /**
     * @return ResultStatusCollector
     */
    public function getResultStatusCollector(): ResultStatusCollector
    {
        if ($this->resultStatusCollector === null) {
            $this->resultStatusCollector = ResultStatusCollector::new();
        }
        return $this->resultStatusCollector;
    }

    /**
     * @param ResultStatusCollector $resultStatusCollector
     * @return static
     */
    protected function setResultStatusCollector(ResultStatusCollector $resultStatusCollector): static
    {
        $this->resultStatusCollector = $resultStatusCollector;
        return $this;
    }
}
