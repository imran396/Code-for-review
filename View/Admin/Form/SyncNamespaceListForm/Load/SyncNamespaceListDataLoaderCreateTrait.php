<?php
/**
 * Sync Namespace List Data Loader Create Trait
 *
 * SAM-6282: Refactor Sync Namespace List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 13, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SyncNamespaceListForm\Load;

/**
 * Trait SyncNamespaceListDataLoaderCreateTrait
 */
trait SyncNamespaceListDataLoaderCreateTrait
{
    protected ?SyncNamespaceListDataLoader $syncNamespaceListDataLoader = null;

    /**
     * @return SyncNamespaceListDataLoader
     */
    protected function createSyncNamespaceListDataLoader(): SyncNamespaceListDataLoader
    {
        $syncNamespaceListDataLoader = $this->syncNamespaceListDataLoader ?: SyncNamespaceListDataLoader::new();
        return $syncNamespaceListDataLoader;
    }

    /**
     * @param SyncNamespaceListDataLoader $syncNamespaceListDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setSyncNamespaceListDataLoader(SyncNamespaceListDataLoader $syncNamespaceListDataLoader): static
    {
        $this->syncNamespaceListDataLoader = $syncNamespaceListDataLoader;
        return $this;
    }
}
