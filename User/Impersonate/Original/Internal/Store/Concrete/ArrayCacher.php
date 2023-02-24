<?php
/**
 * Adapter for caching directly in state of this service
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

use Sam\Core\Service\CustomizableClass;
use Sam\User\Impersonate\Original\Internal\Model\OriginalUserIdentity;

/**
 * Class ArrayCacher
 */
class ArrayCacher extends CustomizableClass implements CacherInterface
{
    protected array $data = [];

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
        return $this->data[self::KEY] ?? null;
    }

    /**
     * Check, if original user registered in session
     * @return bool
     */
    public function has(): bool
    {
        return isset($this->data[self::KEY]);
    }

    /**
     * @param OriginalUserIdentity $dto
     */
    public function set(OriginalUserIdentity $dto): void
    {
        $this->data[self::KEY] = $dto;
    }

    public function delete(): void
    {
        unset($this->data[self::KEY]);
    }
}
