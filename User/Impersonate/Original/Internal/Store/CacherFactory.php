<?php
/**
 * SAM-6576: File system key-value caching for visitor session data
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 01, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Impersonate\Original\Internal\Store;

use InvalidArgumentException;
use RuntimeException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\User\Impersonate\Original\Internal\Store\Concrete\ArrayCacher;
use Sam\User\Impersonate\Original\Internal\Store\Concrete\CacherInterface;
use Sam\User\Impersonate\Original\Internal\Store\Concrete\FileSessionCacher;
use Sam\User\Impersonate\Original\Internal\Store\Concrete\NativeSessionCacher;

/**
 * Class CacherFactory
 * @package Sam\User\Impersonate\Original\Internal\Store
 */
class CacherFactory extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    protected const CACHER_CLASSES = [
        Constants\UserImpersonate::CA_NATIVE => NativeSessionCacher::class,
        Constants\UserImpersonate::CA_FILE => FileSessionCacher::class,
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string|null $adapterType null to initiate default adapter defined in installation configuration
     * @return CacherInterface
     */
    public function create(string $adapterType = null): CacherInterface
    {
        $adapterType = $adapterType ?? $this->cfg()->get('core->user->impersonate->cacher');
        $adapterClass = self::CACHER_CLASSES[$adapterType] ?? null;
        if (!$adapterClass) {
            throw new InvalidArgumentException("Cacher implementation cannot be found for \"{$adapterType}\" type");
        }

        if (
            session_status() !== PHP_SESSION_ACTIVE
            && in_array($adapterType, [Constants\UserImpersonate::CA_NATIVE, Constants\UserImpersonate::CA_FILE], true)
        ) {
            log_debug('Replace Cacher adapter from NativeSessionCacher to ArrayCacher, because native php session has not been started');
            $adapterClass = ArrayCacher::class;
        }

        $cacher = call_user_func([$adapterClass, 'new']);
        if (!$cacher instanceof CacherInterface) {
            throw new RuntimeException("Cacher implementation does not support CacherInterface");
        }
        return $cacher;
    }
}
