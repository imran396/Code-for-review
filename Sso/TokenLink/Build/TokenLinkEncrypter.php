<?php
/**
 * Encrypt token link
 *
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 3, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Build;

use RuntimeException;
use Sam\Core\Format\Base64\Base64Encoder;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Sso\TokenLink\Config\TokenLinkConfiguratorAwareTrait;

/**
 * Class TokenLink
 * @package Sam\Sso\TokenLink
 */
class TokenLinkEncrypter extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use TokenLinkBuilderHelperAwareTrait;
    use TokenLinkConfiguratorAwareTrait;

    private const ERROR_ENCRYPT_DATA = 'Failed to encrypt data';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Use base64url_encode instead of base64_encode if cfg()->core->sso->tokenLink->base64Url is true
     * @param string $data
     * @return string
     */
    public function base64Encode(string $data): string
    {
        $configurator = $this->getTokenLinkConfigurator();
        if ($configurator->isBase64Url()) {
            return Base64Encoder::new()->construct()->encode($data);
        }
        return base64_encode($data);
    }

    /**
     * Encrypt (username, timestamp), return encrypted string
     * @param string|null $username
     * @param int|null $timestamp
     * @return string
     */
    public function encrypt(?string $username, ?int $timestamp): string
    {
        $configurator = $this->getTokenLinkConfigurator();
        $data = implode($configurator->getUserDataSeparator(), [$username, $timestamp]);
        log_debug(composeLogData(['Concatenated data' => $data . ' (' . strlen($data) . ' bytes)']));

        $data = $this->getTokenLinkBuilderHelper()->uppercaseAndCharset($data);
        log_debug(composeLogData(['Uppercase and charset adjusted' => $data . ' (' . strlen($data) . ' bytes)']));

        $randomBytes = $this->getTokenLinkBuilderHelper()->generateRandomBites();
        log_debug(composeLogData(['IV as hex' => bin2hex($randomBytes) . ' (' . strlen($randomBytes) . ' bytes)']));

        $data = openssl_encrypt(
            $data,
            $configurator->getCipher(),
            $configurator->getPassphrase(),
            OPENSSL_RAW_DATA,
            $randomBytes
        );
        log_debug(composeLogData(['Encrypted data as hex' => bin2hex($data) . ' (' . strlen($data) . ' bytes)']));

        if ($data === false) {
            log_error(self::ERROR_ENCRYPT_DATA . ' for ' . $username . ', ' . $timestamp);
            throw new RuntimeException(self::ERROR_ENCRYPT_DATA);
        }

        $result = implode(
            $this->getTokenLinkConfigurator()->getInternalSeparator(),
            [$this->base64Encode($data), $this->base64Encode($randomBytes)]
        );
        log_debug(
            composeLogData(
                [
                    'Concatenated base64 encoded encrypted result' => $result . ' (' . strlen($result) . ' bytes)',
                ]
            )
        );
        return $result;
    }
}
