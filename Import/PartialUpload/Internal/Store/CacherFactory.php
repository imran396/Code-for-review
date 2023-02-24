<?php
/**
 * Initiate default adapter for storing partial upload data (array, file, session)
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

use InvalidArgumentException;
use RuntimeException;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\PartialUpload\Internal\Store\Concrete\CacherInterface;
use Sam\Import\PartialUpload\Internal\Store\Concrete\NativeSessionCacher;

/**
 * Class CacherFactory
 * @package namespace Sam\Upload\Internal\Store;
 */
class CacherFactory extends CustomizableClass
{
    const CA_NATIVE = 'native';

    protected const CACHER_CLASSES = [
        self::CA_NATIVE => NativeSessionCacher::class,
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
        $adapterType = $adapterType ?: self::CA_NATIVE;
        $adapterClass = self::CACHER_CLASSES[$adapterType] ?? null;
        if (!$adapterClass) {
            throw new InvalidArgumentException("Cacher implementation cannot be found for \"{$adapterType}\" type");
        }

        $cacher = call_user_func([$adapterClass, 'new']);
        if (!$cacher instanceof CacherInterface) {
            throw new RuntimeException("Cacher implementation does not support CacherInterface");
        }
        return $cacher;
    }
}
