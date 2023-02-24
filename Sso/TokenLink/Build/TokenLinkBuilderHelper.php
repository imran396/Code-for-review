<?php
/**
 * Help to build a token link
 *
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 31, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Build;

use Error;
use RuntimeException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Sso\TokenLink\Config\TokenLinkConfiguratorAwareTrait;

/**
 * Class TokenLinkBuilderHelper
 * @package Sam\Sso\TokenLink
 */
class TokenLinkBuilderHelper extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use TokenLinkConfiguratorAwareTrait;

    public const ERROR_GENERATE_RANDOM_BITES = 'Failed to generate random iv string';
    public const ERROR_GENERATOR_NOT_CRYPTO_STRONG = 'Random byte generator not crypto strong';
    public const ERROR_INVALID_CIPHER = 'Failed to get cipher iv length. Possibly an invalid cipher: ';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Calculate cipher length if cfg()->core->sso->tokenLink->saltLength is not set
     * @return int
     * @throws RuntimeException
     */
    public function calculateCipherLength(): int
    {
        $saltLength = $this->getTokenLinkConfigurator()->getSaltLength();
        if ($saltLength) {
            return $saltLength;
        }

        $cipher = $this->getTokenLinkConfigurator()->getCipher();
        if (!in_array($cipher, openssl_get_cipher_methods(), true)) {
            throw new RuntimeException(self::ERROR_INVALID_CIPHER . $cipher);
        }

        $cipherLength = openssl_cipher_iv_length($cipher);
        return $cipherLength;
    }

    /**
     * @param string $data
     * @return string
     */
    public function charsetAndLowercase(string $data): string
    {
        $charset = $this->getTokenLinkConfigurator()->getCharset();
        if ($charset) {
            $data = mb_convert_encoding($data, Constants\TokenLink::INTERNAL_CHARSET, $charset);
        }
        if ($this->getTokenLinkConfigurator()->isUppercase()) {
            $data = strtolower($data);
        }
        return $data;
    }

    /**
     * @return string
     * @throws RuntimeException
     */
    public function generateRandomBites(): string
    {
        $configurator = $this->getTokenLinkConfigurator();
        try {
            $randomBytes = openssl_random_pseudo_bytes($this->calculateCipherLength(), $cryptoStrong);
        } catch (Error) {
            throw new RuntimeException(self::ERROR_GENERATE_RANDOM_BITES);
        }
        if (!$randomBytes) {
            throw new RuntimeException(self::ERROR_GENERATE_RANDOM_BITES);
        }
        if (
            !$cryptoStrong
            && $configurator->isCryptoStrongRnd()
        ) {
            throw new RuntimeException(self::ERROR_GENERATOR_NOT_CRYPTO_STRONG);
        }
        return $randomBytes;
    }

    /**
     * @param string $data
     * @return string
     */
    public function uppercaseAndCharset(string $data): string
    {
        if ($this->getTokenLinkConfigurator()->isUppercase()) {
            $data = strtoupper($data);
        }
        $charset = $this->getTokenLinkConfigurator()->getCharset();
        if ($charset) {
            $data = mb_convert_encoding($data, $charset, Constants\TokenLink::INTERNAL_CHARSET);
        }
        return $data;
    }
}
