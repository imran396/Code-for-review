<?php
/**
 * SAM-5739: RTB ping
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Server\ConnectionQuality;


/**
 * Trait ConnectionQualityCheckerAwareTrait
 * @package Sam\Rtb\Server\ConnectionQuality
 */
trait ConnectionQualityCheckerAwareTrait
{
    /**
     * @var ConnectionQualityChecker|null
     */
    protected ?ConnectionQualityChecker $connectionQualityChecker = null;

    /**
     * @return ConnectionQualityChecker
     */
    protected function getConnectionQualityChecker(): ConnectionQualityChecker
    {
        if ($this->connectionQualityChecker === null) {
            $this->connectionQualityChecker = ConnectionQualityChecker::new();
        }
        return $this->connectionQualityChecker;
    }

    /**
     * @param ConnectionQualityChecker $connectionQualityChecker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setConnectionQualityChecker(ConnectionQualityChecker $connectionQualityChecker): static
    {
        $this->connectionQualityChecker = $connectionQualityChecker;
        return $this;
    }
}
