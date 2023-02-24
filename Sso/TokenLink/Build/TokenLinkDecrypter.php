<?php
/**
 * Decrypt token link
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

use RuntimeException;
use Sam\Core\Format\Base64\Base64Decoder;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Sso\TokenLink\Config\TokenLinkConfiguratorAwareTrait;

/**
 * Class TokenLinkDerypter
 * @package Sam\Sso\TokenLink
 */
class TokenLinkDecrypter extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use TokenLinkBuilderHelperAwareTrait;
    use TokenLinkConfiguratorAwareTrait;

    public const ERROR_DECODE_DATA = 'Failed to base64 decode data';
    public const ERROR_DECODE_IV = 'Failed to base64 decode iv';
    public const ERROR_DECRYPT_DATA = 'Failed to decrypt data';
    public const ERROR_INVALID_DATA = 'Invalid data';
    public const ERROR_IV_LENGTH = 'IV is not equal to cipher IV length';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Use base64url_decode instead of base64_decode if cfg()->core->sso->tokenLink->base64Url is true
     * @param string $data
     * @return string|false
     */
    public function base64decode(string $data): string|false
    {
        $configurator = $this->getTokenLinkConfigurator();
        if ($configurator->isBase64Url()) {
            return Base64Decoder::new()->construct()->decode($data);
        }
        return base64_decode($data);
    }

    /**
     * Decrypt token, return [username, timestamp] array
     * @param string $text Data (username, timestamp) + signature
     * @return array = [
     *     'username' => string,
     *     'timestamp' => int
     * ]
     * @throws RuntimeException
     */
    public function decrypt(string $text): array
    {
        $configurator = $this->getTokenLinkConfigurator();
        log_debug(composeLogData(['Encrypted base64 encoded concatenated data' => $text]));

        $data = explode($this->getTokenLinkConfigurator()->getInternalSeparator(), $text);

        if (!is_array($data) || count($data) !== 2) {
            log_error(self::ERROR_INVALID_DATA . ' for: ' . $text);
            throw new RuntimeException(self::ERROR_INVALID_DATA);
        }

        log_debug(composeLogData(['Base64 encoded data' => $data[0] . '; Base64 encoded IV: ' . $data[1]]));

        $objectData = $this->base64decode($data[0]);
        if ($objectData === false) {
            log_error(composeLogData([self::ERROR_DECODE_DATA . ' for' => $text]));
            throw new RuntimeException(self::ERROR_DECODE_DATA);
        }

        $iv = $this->base64decode($data[1]);
        log_debug(composeLogData(['IV (as hex)' => bin2hex($iv) . ' (' . strlen($iv) . ' bytes)']));
        if ($iv === false) {
            log_error(composeLogData([self::ERROR_DECODE_IV . ' for' => $text]));
            throw new RuntimeException(self::ERROR_DECODE_IV);
        }

        if (openssl_cipher_iv_length($configurator->getCipher()) !== strlen($iv)) {
            log_error(composeLogData([self::ERROR_IV_LENGTH . ' for' => $text]));
            throw new RuntimeException(self::ERROR_IV_LENGTH);
        }

        $data = openssl_decrypt(
            $objectData,
            $configurator->getCipher(),
            $configurator->getPassphrase(),
            OPENSSL_RAW_DATA,
            $iv
        );
        log_debug(composeLogData(['Decrypted data' => $data . ' (' . strlen($data) . ' bytes)']));
        if ($data === false) {
            log_error(composeLogData([self::ERROR_DECRYPT_DATA . ' for' => $text]));
            throw new RuntimeException(self::ERROR_DECRYPT_DATA);
        }

        $data = $this->getTokenLinkBuilderHelper()->charsetAndLowercase($data);
        log_debug(composeLogData(['To lower case' => $data]));

        $parts = explode($configurator->getUserDataSeparator(), $data);
        $result = [
            'username' => $parts[0] ?? '',
            'timestamp' => (int)($parts[1] ?? 0),
        ];
        log_debug(composeLogData(['Result' => json_encode($result)]));
        return $result;
    }
}
