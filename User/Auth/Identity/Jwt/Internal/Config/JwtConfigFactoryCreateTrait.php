<?php
/**
 * SAM-5181: Implement JWT (Json Web Tokens) for authorization
 * SAM-10252: SAM migration to PHP 8.1: Upgrade lcobucci/jwt to v4
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Auth\Identity\Jwt\Internal\Config;

/**
 * Trait JwtConfigFactoryCreateTrait
 * @package Sam\User\Auth\Identity\Jwt\Internal\Config
 */
trait JwtConfigFactoryCreateTrait
{
    protected ?JwtConfigFactory $jwtConfigFactory = null;

    /**
     * @return JwtConfigFactory
     */
    protected function createJwtConfigFactory(): JwtConfigFactory
    {
        return $this->jwtConfigFactory ?: JwtConfigFactory::new();
    }

    /**
     * @param JwtConfigFactory $jwtConfigFactory
     * @return static
     * @internal
     */
    public function setJwtConfigFactory(JwtConfigFactory $jwtConfigFactory): static
    {
        $this->jwtConfigFactory = $jwtConfigFactory;
        return $this;
    }
}
