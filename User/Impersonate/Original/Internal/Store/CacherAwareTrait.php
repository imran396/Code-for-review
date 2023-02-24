<?php
/**
 * Accessor trait for cacher adapter. By default trait's property initialization choice is based on configuration.
 *
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

namespace Sam\User\Impersonate\Original\Internal\Store;

use Sam\User\Impersonate\Original\Internal\Store\Concrete\CacherInterface;

/**
 * Trait CacherAwareTrait
 * @package
 */
trait CacherAwareTrait
{
    protected ?CacherInterface $cacher = null;

    /**
     * @return CacherInterface
     */
    protected function getCacher(): CacherInterface
    {
        if ($this->cacher === null) {
            $this->cacher = CacherFactory::new()->create();
        }
        return $this->cacher;
    }

    /**
     * @param CacherInterface $cacher
     * @return $this
     * @internal
     */
    public function setCacher(CacherInterface $cacher): static
    {
        $this->cacher = $cacher;
        return $this;
    }
}
