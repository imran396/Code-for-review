<?php
/**
 * SAM-6576: File system key-value caching for visitor session data
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Cache\File\FileSession;

/**
 * Trait FileSessionCacherAwareTrait
 */
trait FileSessionCacherAwareTrait
{
    /**
     * @var FileSessionCacher|null
     */
    protected ?FileSessionCacher $sessionFileCacher = null;

    /**
     * @return FileSessionCacher
     */
    protected function getSessionFileCacher(): FileSessionCacher
    {
        if ($this->sessionFileCacher === null) {
            $this->sessionFileCacher = FileSessionCacher::new();
        }
        return $this->sessionFileCacher;
    }

    /**
     * @param FileSessionCacher $sessionFileCacher
     * @return $this
     * @internal
     */
    public function setSessionFileCacher(FileSessionCacher $sessionFileCacher): static
    {
        $this->sessionFileCacher = $sessionFileCacher;
        return $this;
    }
}
