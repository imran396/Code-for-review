<?php
/**
 * SAM-6081: Apply FileManagerCreateTrait
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           5/13/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\File\Manage;

use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Trait FileManagerCreateTrait
 * @package
 */
trait FileManagerCreateTrait
{
    protected ?FileManagerInterface $fileManager = null;

    /**
     * Return file manager which is used to manage local and remote file.
     * @return FileManagerInterface fileManager
     */
    protected function createFileManager(): FileManagerInterface
    {
        return $this->fileManager ?: call_user_func([ConfigRepository::getInstance()->get('core->filesystem->managerClass'), 'new']);
    }

    /**
     * @param FileManagerInterface $fileManager
     * @return $this
     */
    public function setFileManager(FileManagerInterface $fileManager): static
    {
        $this->fileManager = $fileManager;
        return $this;
    }
}
