<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/2/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Load;

/**
 * Trait FileContentLoaderAwareTrait
 * @package Sam\Installation\Config
 */
trait FileContentLoaderCreateTrait
{
    /**
     * @var FileContentLoader|null
     */
    protected ?FileContentLoader $fileContentLoader = null;

    /**
     * @return FileContentLoader
     */
    protected function createFileContentLoader(): FileContentLoader
    {
        return $this->fileContentLoader ?: FileContentLoader::new();
    }

    /**
     * @param FileContentLoader $fileContentLoader
     * @return static
     * @internal
     */
    public function setFileContentLoader(FileContentLoader $fileContentLoader): static
    {
        $this->fileContentLoader = $fileContentLoader;
        return $this;
    }
}
