<?php
/**
 * SAM-5726: Application redirector
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/11/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Redirect;

/**
 * Trait ApplicationRedirectorCreateTrait
 * @package Sam\Application\Redirect
 */
trait ApplicationRedirectorCreateTrait
{
    protected ?ApplicationRedirector $applicationRedirector = null;

    /**
     * @return ApplicationRedirector
     */
    protected function createApplicationRedirector(): ApplicationRedirector
    {
        return $this->applicationRedirector ?: ApplicationRedirector::new();
    }

    /**
     * @param ApplicationRedirector $applicationRedirector
     * @return static
     * @internal
     */
    public function setApplicationRedirector(ApplicationRedirector $applicationRedirector): static
    {
        $this->applicationRedirector = $applicationRedirector;
        return $this;
    }
}
