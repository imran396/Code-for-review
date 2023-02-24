<?php
/**
 * SAM-5001: Entity save action retry handler
 * SAM-2126: DB code should effectively be prepared for failing transaction
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/27/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\Save;

/**
 * Trait OlcRetryerAwareTrait
 * @package Sam\Storage\Entity\Save
 */
trait OlcRetryerAwareTrait
{
    protected ?OlcRetryer $olcRetryer = null;

    /**
     * @return OlcRetryer
     */
    protected function getOlcRetryer(): OlcRetryer
    {
        if ($this->olcRetryer === null) {
            $this->olcRetryer = OlcRetryer::new();
        }
        return $this->olcRetryer;
    }

    /**
     * @param OlcRetryer $olcRetryer
     * @return static
     * @internal
     */
    public function setOlcRetryer(OlcRetryer $olcRetryer): static
    {
        $this->olcRetryer = $olcRetryer;
        return $this;
    }
}
