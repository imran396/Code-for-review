<?php
/**
 * Interface for cache adapters that works with original user,
 * who performs impersonation operation and whom we want to return back later.
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

use Sam\User\Impersonate\Original\Internal\Model\OriginalUserIdentity;
use Sam\Core\Constants;

interface CacherInterface
{
    /**
     * Original user - is the source user, who performs impersonation
     * @var string we store original user data under this key
     */
    public const KEY = Constants\SessionCache::IMPERSONATE;

    /**
     * Read original user data from storage
     * @return OriginalUserIdentity|null
     */
    public function get(): ?OriginalUserIdentity;

    /**
     * Check if original user data exists in storage
     * @return bool
     */
    public function has(): bool;

    /**
     * Write original user data to storage
     * @param OriginalUserIdentity $dto
     */
    public function set(OriginalUserIdentity $dto): void;

    /**
     * Remove original user data from storage
     */
    public function delete(): void;
}
