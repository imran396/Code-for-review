<?php
/**
 * SAM-10418: Extract public site view language updating to separate service
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 08, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Language\Update;

/**
 * Trait ApplicationLanguageUpdaterCreateTrait
 * @package Sam\Application\Language\Update
 */
trait ApplicationLanguageUpdaterCreateTrait
{
    protected ?ApplicationLanguageUpdater $applicationLanguageUpdater = null;

    /**
     * @return ApplicationLanguageUpdater
     */
    protected function createApplicationLanguageUpdater(): ApplicationLanguageUpdater
    {
        return $this->applicationLanguageUpdater ?: ApplicationLanguageUpdater::new();
    }

    /**
     * @param ApplicationLanguageUpdater $applicationLanguageUpdater
     * @return $this
     * @internal
     */
    public function setApplicationLanguageUpdater(ApplicationLanguageUpdater $applicationLanguageUpdater): static
    {
        $this->applicationLanguageUpdater = $applicationLanguageUpdater;
        return $this;
    }
}
