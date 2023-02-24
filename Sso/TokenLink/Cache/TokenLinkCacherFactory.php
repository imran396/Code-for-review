<?php
/**
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Cache;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Sso\TokenLink\Cache\File\TokenLinkFileCacher;
use Sam\Sso\TokenLink\Config\TokenLinkConfiguratorAwareTrait;

/**
 * Class TokenLinkCacherFactory
 * @package
 */
class TokenLinkCacherFactory extends CustomizableClass
{
    use TokenLinkConfiguratorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create cacher instance according to config setting
     * @return TokenLinkCacherInterface
     * @throws \InvalidArgumentException
     */
    public function create(): TokenLinkCacherInterface
    {
        return $this->createByType($this->getTokenLinkConfigurator()->getCacheType());
    }

    /**
     * Create cacher instance by type
     * @param $cacheType
     * @return TokenLinkCacherInterface
     * @throws \InvalidArgumentException
     */
    public function createByType($cacheType): TokenLinkCacherInterface
    {
        if ($cacheType === Constants\TokenLink::CACHE_FILE) {
            $cacher = TokenLinkFileCacher::new();
        } else {
            throw new \InvalidArgumentException(sprintf('Unknown cache type "%s"', $cacheType));
        }
        return $cacher;
    }
}
