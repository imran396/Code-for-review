<?php
/**
 * Adapter for caching with help of key-value file storage based on session id
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

namespace Sam\User\Impersonate\Original\Internal\Store\Concrete;

use Sam\Cache\File\FileSession\FileSessionCacherAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Impersonate\Original\Internal\Model\OriginalUserIdentity;

/**
 * Class FileSessionCacher
 */
class FileSessionCacher extends CustomizableClass implements CacherInterface
{
    use FileSessionCacherAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return OriginalUserIdentity|null
     */
    public function get(): ?OriginalUserIdentity
    {
        return $this->getSessionFileCacher()->get(self::KEY);
    }

    /**
     * Check, if original user registered in session
     * @return bool
     */
    public function has(): bool
    {
        return $this->getSessionFileCacher()->has(self::KEY);
    }

    /**
     * @param OriginalUserIdentity $dto
     */
    public function set(OriginalUserIdentity $dto): void
    {
        $this->getSessionFileCacher()->set(self::KEY, $dto);
    }

    public function delete(): void
    {
        $this->getSessionFileCacher()->delete(self::KEY);
    }
}
