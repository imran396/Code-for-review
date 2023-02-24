<?php
/**
 * SAM-4158: Move encryption logic and replace QCryptography with modern library
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Security\Crypt\Legacy;

use Laminas\Crypt\Exception\RuntimeException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BlockCipher
 * @package Sam\Security\Crypt
 */
class LegacyBlockCipher extends CustomizableClass
{
    protected LegacyOpensslAdapter $cipher;
    protected string $outputMode;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * BlockCipher constructor.
     * @param string $key
     * @param string $outputMode
     * @return static
     */
    public function construct(
        string $key,
        string $outputMode = Constants\Cryptography::OUTPUT_BINARY
    ): static {
        $this->cipher = $this->createCipher($key, '3des', '');
        $this->outputMode = $outputMode;
        return $this;
    }

    /**
     * @param string $key
     * @param string $algo
     * @param string $mode
     * @return LegacyOpensslAdapter
     */
    protected function createCipher(
        string $key,
        string $algo,
        string $mode
    ): LegacyOpensslAdapter {
        $cipher = new LegacyOpensslAdapter(['algo' => $algo, 'mode' => $mode]);

        $key = $this->hashKey($key);
        $cipher->setKey($key);
        return $cipher;
    }

    /**
     * Decrypt
     *
     * @param string $data
     * @return string
     */
    public function decrypt(string $data): string
    {
        if ($data === '') {
            return '';
        }

        $cipherText = $data;
        if (!$this->isBinaryOutput()) {
            $cipherText = $this->convertLegacyBase64($cipherText);
            $cipherText = base64_decode($cipherText);
        }

        try {
            $decrypted = $this->cipher->decrypt($cipherText);
            $output = $decrypted
                ? $this->stripLegacyLengthHeader($decrypted)
                : $data;
        } catch (RuntimeException) {
            $output = $data;
        }
        return $output;
    }

    /**
     * Convert legacy base64 string to valid base64 for compatibility.
     * In old implementation '/' has been replaced to to '_' and '+' to '-'. Why?
     *
     * @param string $legacyBase64
     * @return string
     */
    protected function convertLegacyBase64(string $legacyBase64): string
    {
        $base64 = str_replace(['_', '-'], ['/', '+'], $legacyBase64);
        return $base64;
    }

    /**
     * Strip legacy length header {length}/{data} for compatibility.
     * The new implementation uses padding to extend data to the cipher block size
     *
     * @param string $data
     * @return string
     */
    protected function stripLegacyLengthHeader(string $data): string
    {
        // Figure Out Length and Truncate
        $delimiterPosition = strpos($data, '/');
        if (!$delimiterPosition) { //No legacy length header
            return $data;
        }
        $pureData = substr($data, $delimiterPosition + 1);
        return $pureData;
    }

    /**
     * @return bool
     */
    protected function isBinaryOutput(): bool
    {
        return $this->outputMode === Constants\Cryptography::OUTPUT_BINARY;
    }

    /**
     * @param string $key
     * @return string
     */
    protected function hashKey(string $key): string
    {
        return md5($key);
    }
}
