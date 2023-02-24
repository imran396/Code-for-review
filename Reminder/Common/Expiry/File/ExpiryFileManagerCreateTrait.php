<?php
/**
 * SAM-9734: Fix email reminder behavior for the case when last run timestamps are missed
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Reminder\Common\Expiry\File;

/**
 * Trait ExpiryFileManagerCreateTrait
 * @package Sam\Reminder\Common\Expiry\File
 */
trait ExpiryFileManagerCreateTrait
{
    protected ?ExpiryFileManager $expiryFileManager = null;

    /**
     * @return ExpiryFileManager
     */
    protected function createExpiryFileManager(): ExpiryFileManager
    {
        return $this->expiryFileManager ?: ExpiryFileManager::new();
    }

    /**
     * @param ExpiryFileManager $expiryFileManager
     * @return $this
     * @internal
     */
    public function setExpiryFileManager(ExpiryFileManager $expiryFileManager): static
    {
        $this->expiryFileManager = $expiryFileManager;
        return $this;
    }
}
