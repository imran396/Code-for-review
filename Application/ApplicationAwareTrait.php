<?php
/**
 * Trait that implements Application property and accessors
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           24 Feb, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application;

/**
 * Trait ApplicationAwareTrait
 * @package Sam\Application
 */
trait ApplicationAwareTrait
{
    protected ?Application $application = null;

    /**
     * @return Application
     */
    protected function getApplication(): Application
    {
        // Don't store instance of Singleton class in object property, but directly access via getInstance() in getter
        return $this->application ?: Application::getInstance();
    }

    /**
     * @param Application $application
     * @return static
     * @internal
     */
    public function setApplication(Application $application): static
    {
        $this->application = $application;
        return $this;
    }
}
