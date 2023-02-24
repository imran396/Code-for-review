<?php
/**
 * SAM-4158: Move encryption logic and replace QCryptography with modern library
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Security\Crypt;

use InvalidArgumentException;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class BlockCipherProvider
 * @package Sam\Security\Crypt
 */
class BlockCipherProvider extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use OptionalsTrait;

    public const OP_BLOWFISH = 'blowfish';
    public const OP_CRYPTOGRAPHY_ALGORITHM = 'cryptographyAlgorithm';
    public const OP_CRYPTOGRAPHY_MODE = 'cryptographyMode';
    public const OP_ENCRYPTION_KEY = 'encryptionKey'; // string

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @return BlockCipher
     */
    public function createCipher(): BlockCipher
    {
        $encryptionKey = (string)$this->fetchOptional(self::OP_ENCRYPTION_KEY);
        if (!$encryptionKey) {
            throw new InvalidArgumentException('Please provide core->security->cryptography->encryptionKey config option. It cannot be empty');
        }
        $algo = (string)$this->fetchOptional(self::OP_CRYPTOGRAPHY_ALGORITHM);
        $mode = (string)$this->fetchOptional(self::OP_CRYPTOGRAPHY_MODE);
        $blockCipher = BlockCipher::new()->construct($encryptionKey, $algo, $mode);
        return $blockCipher;
    }

    /**
     * @return Legacy\LegacyBlockCipher
     */
    public function createLegacyCipher(): Legacy\LegacyBlockCipher
    {
        $blowfish = (string)$this->fetchOptional(self::OP_BLOWFISH);
        $blockCipher = Legacy\LegacyBlockCipher::new()->construct($blowfish);
        return $blockCipher;
    }

    /**
     * @param string|null $data
     * @return string
     */
    public function encrypt(?string $data): string
    {
        if (!$data) {
            return '';
        }
        return $this->createCipher()->encrypt($data);
    }

    /**
     * @param string|null $data
     * @return string
     */
    public function decrypt(?string $data): string
    {
        if (!$data) {
            return '';
        }
        $result = $this->createCipher()->decrypt($data);
        if (!$result) {
            $result = $this->createLegacyCipher()->decrypt($data);
        }
        return $result;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_CRYPTOGRAPHY_ALGORITHM] = $optionals[self::OP_CRYPTOGRAPHY_ALGORITHM]
            ?? $this->cfg()->get('core->security->cryptography->algorithm');
        $optionals[self::OP_CRYPTOGRAPHY_MODE] = $optionals[self::OP_CRYPTOGRAPHY_MODE]
            ?? $this->cfg()->get('core->security->cryptography->mode');
        $optionals[self::OP_ENCRYPTION_KEY] = $optionals[self::OP_ENCRYPTION_KEY]
            ?? $this->cfg()->get('core->security->cryptography->encryptionKey');
        $optionals[self::OP_BLOWFISH] = $optionals[self::OP_BLOWFISH]
            ?? $this->cfg()->get('core->general->blowfish');
        $this->setOptionals($optionals);
    }
}
