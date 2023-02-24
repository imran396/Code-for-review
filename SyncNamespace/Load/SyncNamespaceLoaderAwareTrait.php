<?php
/**
 * SAM-4741: SyncNamespace loader and existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-02-10
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SyncNamespace\Load;

/**
 * Trait SyncNamespaceLoaderAwareTrait
 * @package Sam\SyncNamespace\Load
 */
trait SyncNamespaceLoaderAwareTrait
{
    protected ?SyncNamespaceLoader $syncNamespaceLoader = null;

    /**
     * @return SyncNamespaceLoader
     */
    protected function getSyncNamespaceLoader(): SyncNamespaceLoader
    {
        if ($this->syncNamespaceLoader === null) {
            $this->syncNamespaceLoader = SyncNamespaceLoader::new();
        }
        return $this->syncNamespaceLoader;
    }

    /**
     * @param SyncNamespaceLoader $syncNamespaceLoader
     * @return static
     * @internal
     */
    public function setSyncNamespaceLoader(SyncNamespaceLoader $syncNamespaceLoader): static
    {
        $this->syncNamespaceLoader = $syncNamespaceLoader;
        return $this;
    }
}
