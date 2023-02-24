<?php
/**
 * SAM-4712 : Refactor Tell Friend service
 * https://bidpath.atlassian.net/browse/SAM-4712
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/21/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\File;

/**
 * Trait FilePathHelperAwareTrait
 * @package Sam\File
 */
trait FilePathHelperAwareTrait
{
    /**
     * @var FilePathHelper|null
     */
    protected ?FilePathHelper $filePathHelper = null;

    /**
     * @return FilePathHelper
     */
    protected function getFilePathHelper(): FilePathHelper
    {
        if ($this->filePathHelper === null) {
            $this->filePathHelper = FilePathHelper::new();
        }
        return $this->filePathHelper;
    }

    /**
     * @param FilePathHelper $filePathHelper
     * @return static
     * @internal
     */
    public function setFilePathHelper(FilePathHelper $filePathHelper): static
    {
        $this->filePathHelper = $filePathHelper;
        return $this;
    }
}
