<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           4/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Save\RawInput;

/**
 * Trait RawInputCollectorAwareTrait
 * @package Sam\Core\Save\RawInput
 */
trait RawInputCollectorAwareTrait
{
    /**
     * @var RawInputCollector|null
     */
    protected ?RawInputCollector $rawInputCollector = null;

    /**
     * @return RawInputCollector
     */
    protected function getRawInputCollector(): RawInputCollector
    {
        if ($this->rawInputCollector === null) {
            $this->rawInputCollector = RawInputCollector::new();
        }
        return $this->rawInputCollector;
    }

    /**
     * @param RawInputCollector $rawInputCollector
     * @return static
     * @internal
     */
    public function setRawInputCollector(RawInputCollector $rawInputCollector): static
    {
        $this->rawInputCollector = $rawInputCollector;
        return $this;
    }
}
