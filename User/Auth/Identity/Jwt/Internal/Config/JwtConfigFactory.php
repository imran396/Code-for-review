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

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer;
use RuntimeException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class JwtConfigFactory
 * @package Sam\User\Auth\Identity\Jwt\Internal\Config
 */
class JwtConfigFactory extends CustomizableClass
{
    use CurrentDateTrait;
    use ConfigRepositoryAwareTrait;

    protected const FILE_KEY_PREFIX = 'file://';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function create(): Configuration
    {
        $signer = $this->createSigner();
        $config = $this->createForSigner($signer);
        return $config;
    }

    protected function createForSigner(Signer $signer): Configuration
    {
        if ($this->isAsymmetricSignerAlgorithm($signer)) {
            return Configuration::forAsymmetricSigner(
                $signer,
                $this->createKeyFromConfig('core->jwt->security->privateKey'),
                $this->createKeyFromConfig('core->jwt->security->publicKey')
            );
        }
        return Configuration::forSymmetricSigner($signer, $this->createKeyFromConfig('core->jwt->security->privateKey'));
    }

    protected function createKeyFromConfig(string $configKey): Signer\Key
    {
        $keyString = $this->cfg()->get($configKey);
        if (!$keyString) {
            throw new RuntimeException("Key '{$configKey}' for JWT is not specified");
        }
        if (str_starts_with($keyString, self::FILE_KEY_PREFIX)) {
            $path = ltrim($keyString, self::FILE_KEY_PREFIX);
            $key = Signer\Key\InMemory::file($path);
        } else {
            $key = Signer\Key\InMemory::plainText($keyString);
        }

        return $key;
    }

    protected function createSigner(): Signer
    {
        $algorithm = $this->cfg()->get('core->jwt->security->algorithm');
        return match ($algorithm) {
            Constants\Jwt::ALGORITHM_HMAC_SHA256 => new Signer\Hmac\Sha256(),
            Constants\Jwt::ALGORITHM_HMAC_SHA512 => new Signer\Hmac\Sha512(),
            Constants\Jwt::ALGORITHM_RSA_SHA256 => new Signer\Rsa\Sha256(),
            Constants\Jwt::ALGORITHM_RSA_SHA512 => new Signer\Rsa\Sha512(),
            Constants\Jwt::ALGORITHM_ECDSA_SHA256 => Signer\Ecdsa\Sha256::create(),
            Constants\Jwt::ALGORITHM_ECDSA_SHA512 => Signer\Ecdsa\Sha512::create(),
            default => throw new RuntimeException('Invalid JWT algorithm')
        };
    }

    /**
     * @param Signer $signer
     * @return bool
     */
    protected function isAsymmetricSignerAlgorithm(Signer $signer): bool
    {
        return is_a($signer, Signer\Ecdsa::class)
            || is_a($signer, Signer\Rsa::class);
    }
}
