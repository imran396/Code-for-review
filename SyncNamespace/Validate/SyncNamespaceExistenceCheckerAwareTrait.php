<?php
/**
 *
 * SAM-4741: SyncNamespace loader and existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-02-09
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SyncNamespace\Validate;

/**
 * Trait SyncNamespaceExistenceCheckerAwareTrait
 * @package Sam\SyncNamespace\Validate
 */
trait SyncNamespaceExistenceCheckerAwareTrait
{
    protected ?SyncNamespaceExistenceChecker $syncNamespaceExistenceChecker = null;

    /**
     * @return SyncNamespaceExistenceChecker
     */
    protected function getSyncNamespaceExistenceChecker(): SyncNamespaceExistenceChecker
    {
        if ($this->syncNamespaceExistenceChecker === null) {
            $this->syncNamespaceExistenceChecker = SyncNamespaceExistenceChecker::new();
        }
        return $this->syncNamespaceExistenceChecker;
    }

    /**
     * @param SyncNamespaceExistenceChecker $syncNamespaceExistenceChecker
     * @return static
     * @internal
     */
    public function setSyncNamespaceExistenceChecker(SyncNamespaceExistenceChecker $syncNamespaceExistenceChecker): static
    {
        $this->syncNamespaceExistenceChecker = $syncNamespaceExistenceChecker;
        return $this;
    }
}
