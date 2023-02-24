<?php
/**
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Config;

use RuntimeException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class TokenLinkHelper
 * @package
 */
class TokenLinkConfigurator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    private ?bool $base64Url = null;
    private ?int $cacheType = null;
    private ?string $charset = null;
    private ?string $cipher = null;
    private ?bool $isCryptoStrongRnd = null;
    private ?bool $isEnabled = null;
    private ?int $expiration = null;
    private ?string $hashMethod = null;
    private ?string $internalSeparator = null;
    private ?string $passphrase = null;
    private ?int $saltLength = null;
    private ?string $secretColumn = null;
    private ?string $secretTable = null;
    private ?string $secretUserAttribute = null;
    private ?string $tokenParameterName = null;
    private ?bool $isUppercase = null;
    private ?string $userDataSeparator = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function isBase64Url(): bool
    {
        if ($this->base64Url === null) {
            $this->enableBase64Url($this->cfg()->get('core->sso->tokenLink->base64Url'));
        }
        return $this->base64Url;
    }

    /**
     * @param bool $base64Url
     * @return static
     */
    public function enableBase64Url(bool $base64Url): static
    {
        $this->base64Url = $base64Url;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCacheType(): ?int
    {
        if ($this->cacheType === null) {
            $this->setCacheType($this->cfg()->get('core->sso->tokenLink->cacheType'));
        }
        return $this->cacheType;
    }

    /**
     * @param int $cacheType
     * @return static
     */
    public function setCacheType(int $cacheType): static
    {
        $this->cacheType = $cacheType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCharset(): ?string
    {
        if ($this->charset === null) {
            $this->setCharset($this->cfg()->get('core->sso->tokenLink->charset'));
        }
        return $this->charset;
    }

    /**
     * @param string $charset
     * @return static
     */
    public function setCharset(string $charset): static
    {
        $this->charset = trim($charset);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCipher(): ?string
    {
        if ($this->cipher === null) {
            $this->setCipher($this->cfg()->get('core->sso->tokenLink->cipher'));
        }
        return $this->cipher;
    }

    /**
     * @param string $cipher
     * @return static
     */
    public function setCipher(string $cipher): static
    {
        $this->cipher = trim($cipher);
        return $this;
    }

    /**
     * @return bool
     */
    public function isCryptoStrongRnd(): bool
    {
        if ($this->isCryptoStrongRnd === null) {
            $this->enableCryptoStrongRnd($this->cfg()->get('core->sso->tokenLink->cryptoStrongRnd'));
        }
        return $this->isCryptoStrongRnd;
    }

    /**
     * @param bool $cryptoStrongRnd
     * @return static
     */
    public function enableCryptoStrongRnd(bool $cryptoStrongRnd): static
    {
        $this->isCryptoStrongRnd = $cryptoStrongRnd;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFeatureEnabled(): bool
    {
        if ($this->isEnabled === null) {
            $this->enableFeature($this->cfg()->get('core->sso->tokenLink->enabled'));
        }
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     * @return static
     */
    public function enableFeature(bool $isEnabled): static
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getExpiration(): ?int
    {
        if ($this->expiration === null) {
            $this->setExpiration($this->cfg()->get('core->sso->tokenLink->expiration'));
        }
        return $this->expiration;
    }

    /**
     * @param int $expiration
     * @return static
     */
    public function setExpiration(int $expiration): static
    {
        $this->expiration = $expiration;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHashMethod(): ?string
    {
        if ($this->hashMethod === null) {
            $this->setHashMethod($this->cfg()->get('core->sso->tokenLink->hashMethod'));
        }
        return $this->hashMethod;
    }

    /**
     * @param string $hashMethod
     * @return static
     */
    public function setHashMethod(string $hashMethod): static
    {
        $this->hashMethod = trim($hashMethod);
        return $this;
    }

    /**
     * @return non-empty-string
     */
    public function getInternalSeparator(): string
    {
        if ($this->internalSeparator === null) {
            $this->setInternalSeparator($this->cfg()->get('core->sso->tokenLink->internalSeparator'));
        }
        if (!$this->internalSeparator) {
            throw new RuntimeException('Internal separator is not configured properly');
        }
        return $this->internalSeparator;
    }

    /**
     * @param non-empty-string $internalSeparator
     * @return static
     */
    public function setInternalSeparator(string $internalSeparator): static
    {
        $this->internalSeparator = trim($internalSeparator);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassphrase(): ?string
    {
        if ($this->passphrase === null) {
            $this->setPassphrase($this->cfg()->get('core->sso->tokenLink->passphrase'));
        }
        return $this->passphrase;
    }

    /**
     * @param string $passphrase
     * @return static
     */
    public function setPassphrase(string $passphrase): static
    {
        $this->passphrase = trim($passphrase);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSecretColumn(): ?string
    {
        if ($this->secretColumn === null) {
            $this->getSecretTableAndColumn();
        }
        return $this->secretColumn;
    }

    /**
     * @param string $secretColumn
     * @return static
     */
    public function setSecretColumn(string $secretColumn): static
    {
        $this->secretColumn = trim($secretColumn);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSecretTable(): ?string
    {
        if ($this->secretTable === null) {
            $this->getSecretTableAndColumn();
        }
        return $this->secretTable;
    }

    /**
     * @param string $secretTable
     * @return static
     */
    public function setSecretTable(string $secretTable): static
    {
        $this->secretTable = trim($secretTable);
        return $this;
    }

    /**
     * Determine table and column, that stores secret value
     * @return array
     */
    public function getSecretTableAndColumn(): array
    {
        if ($this->secretTable === null || $this->secretColumn === null) {
            if (str_contains($this->getSecretUserAttribute(), Constants\TokenLink::TOKEN_SEPARATOR)) {
                [$secretTable, $secretColumn] = explode(Constants\TokenLink::TOKEN_SEPARATOR, $this->getSecretUserAttribute());
                $this->setSecretTable($secretTable);
                $this->setSecretColumn($secretColumn);
            }
        }
        return [$this->secretTable, $this->secretColumn];
    }

    /**
     * @return string|null
     */
    public function getSecretUserAttribute(): ?string
    {
        if ($this->secretUserAttribute === null) {
            $this->setSecretUserAttribute($this->cfg()->get('core->sso->tokenLink->secretUserAttribute'));
        }
        return $this->secretUserAttribute;
    }

    /**
     * @param string $secretUserAttribute
     * @return static
     */
    public function setSecretUserAttribute(string $secretUserAttribute): static
    {
        $this->secretUserAttribute = trim($secretUserAttribute);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSaltLength(): ?int
    {
        if ($this->saltLength === null) {
            $this->setSaltLength($this->cfg()->get('core->sso->tokenLink->saltLength'));
        }
        return $this->saltLength;
    }

    /**
     * @param int $saltLength
     * @return static
     */
    public function setSaltLength(int $saltLength): static
    {
        $this->saltLength = $saltLength;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTokenParameterName(): ?string
    {
        if ($this->tokenParameterName === null) {
            $this->setTokenParameterName($this->cfg()->get('core->sso->tokenLink->tokenParameterName'));
        }
        return $this->tokenParameterName;
    }

    /**
     * @param string $tokenParameterName
     * @return static
     */
    public function setTokenParameterName(string $tokenParameterName): static
    {
        $this->tokenParameterName = trim($tokenParameterName);
        return $this;
    }

    /**
     * @return bool
     */
    public function isUppercase(): bool
    {
        if ($this->isUppercase === null) {
            $this->enableUppercase($this->cfg()->get('core->sso->tokenLink->uppercase'));
        }
        return $this->isUppercase;
    }

    /**
     * @param bool $isUppercase
     * @return static
     */
    public function enableUppercase(bool $isUppercase): static
    {
        $this->isUppercase = $isUppercase;
        return $this;
    }

    /**
     * @return non-empty-string
     */
    public function getUserDataSeparator(): string
    {
        if ($this->userDataSeparator === null) {
            $this->setUserDataSeparator($this->cfg()->get('core->sso->tokenLink->separator'));
        }
        if (!$this->userDataSeparator) {
            throw new RuntimeException('User data separator is not configured properly');
        }
        return $this->userDataSeparator;
    }

    /**
     * @param non-empty-string $separator
     * @return static
     */
    public function setUserDataSeparator(string $separator): static
    {
        $this->userDataSeparator = $separator;
        return $this;
    }
}
