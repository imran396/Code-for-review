<?php
/**
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           10/14/20
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\File;


/**
 * Trait FolderManagerAwareTrait
 * @package Sam\File
 */
trait FolderManagerAwareTrait
{
    /**
     * @var FolderManager|null
     */
    protected ?FolderManager $folderManager = null;

    /**
     * @return FolderManager
     */
    protected function getFolderManager(): FolderManager
    {
        if ($this->folderManager === null) {
            $this->folderManager = FolderManager::new();
        }
        return $this->folderManager;
    }

    /**
     * @param FolderManager $folderManager
     * @return static
     * @internal
     */
    public function setFolderManager(FolderManager $folderManager): static
    {
        $this->folderManager = $folderManager;
        return $this;
    }
}
