<?php
/**
 * SAM-6576: File system key-value caching for visitor session data
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/19/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Impersonate\Original;

/**
 * Trait OriginalUserManagerCreateTrait
 * @package Sam\User\Impersonate
 */
trait OriginalUserManagerCreateTrait
{
    protected ?OriginalUserManager $originalUserManager = null;

    /**
     * @return OriginalUserManager
     */
    protected function createOriginalUserManager(): OriginalUserManager
    {
        return $this->originalUserManager ?: OriginalUserManager::new();
    }

    /**
     * @param OriginalUserManager $originalUserManager
     * @return $this
     * @internal
     */
    public function setOriginalUserManager(OriginalUserManager $originalUserManager): static
    {
        $this->originalUserManager = $originalUserManager;
        return $this;
    }
}
