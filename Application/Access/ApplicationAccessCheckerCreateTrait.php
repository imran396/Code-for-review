<?php
/**
 * SAM-5419: Access checkers for application features
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Access;

/**
 * Trait ApplicationAccessCheckerCreateTrait
 * @package
 */
trait ApplicationAccessCheckerCreateTrait
{
    protected ?ApplicationAccessChecker $applicationAccessChecker = null;

    /**
     * @return ApplicationAccessChecker
     */
    protected function createApplicationAccessChecker(): ApplicationAccessChecker
    {
        return $this->applicationAccessChecker ?: ApplicationAccessChecker::new();
    }

    /**
     * @param ApplicationAccessChecker $applicationAccessChecker
     * @return static
     * @internal
     */
    public function setApplicationAccessChecker(ApplicationAccessChecker $applicationAccessChecker): static
    {
        $this->applicationAccessChecker = $applicationAccessChecker;
        return $this;
    }
}
