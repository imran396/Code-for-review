<?php
/**
 * Produce token link signature
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
use Sam\Core\Service\CustomizableClass;
use Sam\Sso\TokenLink\Config\TokenLinkConfiguratorAwareTrait;

/**
 * Class TokenLinkSignatureProducer
 * @package Sam\Sso\TokenLink
 */
class TokenLinkSignatureProducer extends CustomizableClass
{
    use TokenLinkBuilderHelperAwareTrait;
    use TokenLinkConfiguratorAwareTrait;
    use TokenLinkEncrypterCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Signature is used as a part of token for security reasons
     * @param string|null $username
     * @param int|null $timestamp
     * @param string $secret
     * @param string|null $salt
     * @return string
     */
    public function produceSignature(?string $username, ?int $timestamp, string $secret, ?string $salt = null): string
    {
        $data = implode($this->getTokenLinkConfigurator()->getUserDataSeparator(), [$username, $timestamp, $secret]);
        log_debug(composeLogData(['Concatenated data' => $data . ' (' . strlen($data) . ' bytes)']));

        $data = $this->getTokenLinkBuilderHelper()->uppercaseAndCharset($data);
        log_debug(composeLogData(['Uppercase and charset conversion' => $data . ' (' . strlen($data) . ' bytes)']));

        $salt = $salt ?: $this->getTokenLinkBuilderHelper()->generateRandomBites();
        log_debug(composeLogData(['Salt (as hex)' => bin2hex($salt) . ' (' . strlen($salt) . ' bytes)']));

        $hashMethod = $this->getTokenLinkConfigurator()->getHashMethod();
        $signature = openssl_digest($salt . $data, $hashMethod, true);
        if ($signature === false) {
            $message = sprintf('Signing failed using %s', $hashMethod);
            log_error($message . ' for ' . $data);
            throw new RuntimeException($message);
        }

        $result = implode(
            $this->getTokenLinkConfigurator()->getInternalSeparator(),
            [
                $this->createTokenLinkEncrypter()->base64Encode($salt),
                $this->createTokenLinkEncrypter()->base64Encode($signature)
            ]
        );
        log_debug(composeLogData(['Concatenated base64 encoded signature' => $result . ' (' . strlen($result) . ' bytes)']));
        return $result;
    }
}
