<?php
/**
 * SAM-8004: Refactor \Util_Storage
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Storage;

trait PhpSessionStorageCreateTrait
{
    /**
     * @var PhpSessionStorageInterface|null
     */
    protected ?PhpSessionStorageInterface $phpSessionStorage = null;

    /**
     * @return PhpSessionStorageInterface
     */
    protected function createPhpSessionStorage(): PhpSessionStorageInterface
    {
        return $this->phpSessionStorage ?: PhpSessionStorage::new();
    }

    /**
     * @param PhpSessionStorageInterface $phpSessionStorage
     * @return static
     * @internal
     */
    public function setPhpSessionStorage(PhpSessionStorageInterface $phpSessionStorage): static
    {
        $this->phpSessionStorage = $phpSessionStorage;
        return $this;
    }
}
