<?php
/**
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/22/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Process;

/**
 * Trait ApplicationProcessGuidManagerCreateTrait
 * @package
 */
trait ApplicationProcessGuidManagerCreateTrait
{
    protected ?ApplicationProcessGuidManager $applicationProcessGuidManager = null;

    /**
     * @return ApplicationProcessGuidManager
     */
    protected function createApplicationProcessGuidManager(): ApplicationProcessGuidManager
    {
        return $this->applicationProcessGuidManager ?: ApplicationProcessGuidManager::new();
    }

    /**
     * @param ApplicationProcessGuidManager $applicationProcessGuidManager
     * @return $this
     * @internal
     */
    public function setApplicationProcessGuidManager(ApplicationProcessGuidManager $applicationProcessGuidManager): static
    {
        $this->applicationProcessGuidManager = $applicationProcessGuidManager;
        return $this;
    }
}
