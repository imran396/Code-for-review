<?php
/**
 * SAM-9677: Refactor \Feed\CategoryGet
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Feed\Internal\Cache;

/**
 * Trait FileCacherCreateTrait
 * @package Sam\Lot\Category\Feed\Internal
 * @internal
 */
trait FileCacherCreateTrait
{
    /**
     * @var FileCacher|null
     */
    protected ?FileCacher $fileCacher = null;

    /**
     * @return FileCacher
     */
    protected function createFileCacher(): FileCacher
    {
        return $this->fileCacher ?: FileCacher::new();
    }

    /**
     * @param FileCacher $fileCacher
     * @return static
     * @internal
     */
    public function setFileCacher(FileCacher $fileCacher): static
    {
        $this->fileCacher = $fileCacher;
        return $this;
    }
}
