<?php
/**
 * SAM-5911: Improve credit card encryption of data stored in the database
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 23, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Security\Crypt;

use Laminas\Crypt\BlockCipher as BaseBlockCipher;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BlockCipher
 * @package Sam\Security\Crypt
 */
class BlockCipher extends CustomizableClass
{
    protected BaseBlockCipher $cipher;

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
     * @param string $algorithm
     * @param string $mode
     * @return static
     */
    public function construct(
        string $key,
        string $algorithm = Constants\Cryptography::BC_ALGORITHM_AES,
        string $mode = Constants\Cryptography::BC_MODE_CBC
    ): static {
        $this->cipher = $this->createCipher($key, $algorithm, $mode);
        return $this;
    }

    /**
     * @param string $key
     * @param string $algorithm
     * @param string $mode
     * @return BaseBlockCipher
     */
    protected function createCipher(string $key, string $algorithm, string $mode): BaseBlockCipher
    {
        $cipher = BaseBlockCipher::factory(
            'openssl',
            [
                'algorithm' => $algorithm,
                'mode' => $mode,
            ]
        );
        $cipher
            ->setKey($key)
            ->setBinaryOutput(false);
        return $cipher;
    }

    /**
     * @param string $data
     * @return string
     */
    public function encrypt(string $data): string
    {
        if ($data === '') {
            return '';
        }
        $encryptedData = $this->cipher->encrypt($data);
        $result = $this->decorateWithAlgorithm($encryptedData);
        return $result;
    }

    /**
     * @param string $data
     * @return string
     */
    public function decrypt(string $data): string
    {
        $result = '';
        $extractedData = $this->extractAlgorithmAndCipherText($data);
        if ($extractedData) {
            $result = $this
                ->createCipher($this->cipher->getKey(), $extractedData['algorithm'], $extractedData['mode'])
                ->decrypt($extractedData['cipherText']);
        }
        return $result ?: '';
    }

    /**
     * @param string $data
     * @return string
     */
    protected function decorateWithAlgorithm(string $data): string
    {
        $cipher = $this->cipher->getCipher();
        return sprintf('$%s$%s$%s$', $cipher->getAlgorithm(), $cipher->getMode(), $data);
    }

    /**
     * @param string $data
     * @return array
     */
    protected function extractAlgorithmAndCipherText(string $data): array
    {
        if (preg_match('/^\$([\w0-9_-]+)\$([\w0-9_-]+)\$(.*)\$$/', $data, $matches)) {
            return [
                'algorithm' => $matches[1],
                'mode' => $matches[2],
                'cipherText' => $matches[3]
            ];
        }
        return [];
    }
}
