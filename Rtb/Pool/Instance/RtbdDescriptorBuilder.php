<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/28/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Instance;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Data\TypeCast\Cast;

/**
 * Class PoolInstanceBuilder
 * @package
 */
class RtbdDescriptorBuilder extends CustomizableClass
{
    use UserTypeAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $attributes
     * @return RtbdDescriptor
     */
    public function fromArray(array $attributes): RtbdDescriptor
    {
        $name = isset($attributes['name'])
            ? trim($attributes['name'])
            : '';
        $publicHost = isset($attributes['publicHost'])
            ? trim($attributes['publicHost'])
            : '';
        $publicPort = isset($attributes['publicPort'])
            ? Cast::toInt($attributes['publicPort'])
            : null;
        $publicPath = isset($attributes['publicPath'])
            ? trim($attributes['publicPath'])
            : '';
        $bindHost = isset($attributes['bindHost'])
            ? trim($attributes['bindHost'])
            : $publicHost;
        $bindPort = isset($attributes['bindPort'])
            ? Cast::toInt($attributes['bindPort'])
            : $publicPort;
        $includeAccountIds = isset($attributes['includeAccount'])
            ? (is_array($attributes['includeAccount'])
                ? $attributes['includeAccount'] : [$attributes['includeAccount']])
            : [];
        $excludeAccountIds = isset($attributes['excludeAccount'])
            ? (is_array($attributes['excludeAccount'])
                ? $attributes['excludeAccount'] : [$attributes['excludeAccount']])
            : [];

        $poolInstance = RtbdDescriptor::new()
            ->setUserType($this->getUserType())
            ->setName($name)
            ->setBindHost($bindHost)
            ->setBindPort($bindPort)
            ->setPublicHost($publicHost)
            ->setPublicPort($publicPort)
            ->setPublicPath($publicPath)
            ->setIncludeAccountIds($includeAccountIds)
            ->setExcludeAccountIds($excludeAccountIds);
        return $poolInstance;
    }
}
