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
class LotCustomFieldFileUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::P_LOT_CUSTOM_FIELD_FILE;

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
     * @param int|null $lotItemId
     * @param int|null $lotCustomFieldId
     * @param string|null $fileName
     * @param array $options
     * @return $this
     */
    public function construct(?int $lotItemId, ?int $lotCustomFieldId, ?string $fileName, array $options = []): static
    {
        $options[UrlConfigConstants::PARAMS] = [$lotItemId, $lotCustomFieldId, $fileName];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int|null $lotItemId
     * @param int|null $lotCustomFieldId
     * @param string|null $fileName
     * @param array $options
     * @return $this
     */
    public function forWeb(?int $lotItemId, ?int $lotCustomFieldId, ?string $fileName, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($lotItemId, $lotCustomFieldId, $fileName, $options);
    }

    /**
     * @param int|null $lotItemId
     * @param int|null $lotCustomFieldId
     * @param string|null $fileName
     * @param array $options
     * @return $this
     */
    public function forRedirect(?int $lotItemId, ?int $lotCustomFieldId, ?string $fileName, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($lotItemId, $lotCustomFieldId, $fileName, $options);
    }

    /**
     * @param int|null $lotItemId
     * @param int|null $lotCustomFieldId
     * @param string|null $fileName
     * @param array $options
     * @return $this
     */
    public function forDomainRule(?int $lotItemId, ?int $lotCustomFieldId, ?string $fileName, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($lotItemId, $lotCustomFieldId, $fileName, $options);
    }

    /**
     * @param int|null $lotItemId
     * @param int|null $lotCustomFieldId
     * @param string|null $fileName
     * @param array $options
     * @return $this
     */
    public function forBackPage(?int $lotItemId, ?int $lotCustomFieldId, ?string $fileName, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($lotItemId, $lotCustomFieldId, $fileName, $options);
    }

    // --- Local query methods ---

    /**
     * @return int|null
     */
    public function lotItemId(): ?int
    {
        return $this->readIntParam(0);
    }

    /**
     * @return int|null
     */
    public function lotCustomFieldId(): ?int
    {
        return $this->readIntParam(1);
    }

    /**
     * @return string|null
     */
    public function fileName(): ?string
    {
        return $this->readStringParam(2);
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
     * @param int $accountId
     * @return string
     */
    public function fileBasePath(int $accountId): string
    {
        return sprintf('%s/%s/%s', PathResolver::UPLOAD_LOT_CUSTOM_FIELD_FILE, $accountId, $this->fileName());
    }
}
