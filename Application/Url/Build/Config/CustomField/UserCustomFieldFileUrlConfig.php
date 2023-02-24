<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep @2, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\CustomField;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Constants;
use Sam\Core\Path\PathResolver;

/**
 * Class SoundUrlConfig
 * @package Sam\Application\Url\Build\Config
 */
class UserCustomFieldFileUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::P_USER_CUSTOM_FIELD_FILE;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Constructors ---

    /**
     * @param int|null $userId
     * @param string|null $fileName
     * @param array $options
     * @return $this
     */
    public function construct(?int $userId, ?string $fileName, array $options = []): static
    {
        $options[UrlConfigConstants::PARAMS] = [$userId, $fileName];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int|null $userId
     * @param string|null $fileName
     * @param array $options
     * @return $this
     */
    public function forWeb(?int $userId, ?string $fileName, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($userId, $fileName, $options);
    }

    /**
     * @param int|null $userId
     * @param string|null $fileName
     * @param array $options
     * @return $this
     */
    public function forRedirect(?int $userId, ?string $fileName, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($userId, $fileName, $options);
    }

    /**
     * @param int|null $userId
     * @param string|null $fileName
     * @param array $options
     * @return $this
     */
    public function forDomainRule(?int $userId, ?string $fileName, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($userId, $fileName, $options);
    }

    /**
     * @param int|null $userId
     * @param string|null $fileName
     * @param array $options
     * @return $this
     */
    public function forBackPage(?int $userId, ?string $fileName, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($userId, $fileName, $options);
    }

    // --- Local query methods ---

    /**
     * @return int|null
     */
    public function userId(): ?int
    {
        return $this->readIntParam(0);
    }

    /**
     * @return string|null
     */
    public function fileName(): ?string
    {
        return $this->readStringParam(1);
    }

    /**
     * Return url path
     * @return string
     */
    public function urlPath(): string
    {
        return dirname($this->urlFilled());
    }

    /**
     * Return base path of file source
     * @return string
     */
    public function fileBasePath(): string
    {
        return sprintf('%s/%s/%s', PathResolver::UPLOAD_USER_CUSTOM_FIELD_FILE, $this->userId(), $this->fileName());
    }
}
