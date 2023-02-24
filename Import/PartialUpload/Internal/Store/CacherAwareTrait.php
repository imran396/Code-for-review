<?php
/**
 * Trait for CacherFactory
 *
 * SAM-6575: Lot Csv Import - Extract session operations to separate adapter
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\PartialUpload\Internal\Store;

use Sam\Import\PartialUpload\Internal\Store\Concrete\CacherInterface;

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
