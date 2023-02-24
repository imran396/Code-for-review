<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\User;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;

/**
 * Class AnySingleUserUrlConfig
 * @package Sam\Application\Url
 */
class AnySingleUserUrlConfig extends AbstractUrlConfig
{
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
     * @param int $urlType
     * @param int|null $userId - pass null when constructing template url for js
     * @param array $options = [
     *     ... // regular options
     *     ... // account pre-loading optionals (JIC, not needed now)
     * ]
     * @return static
     */
    public function construct(int $urlType, ?int $userId, array $options = []): static
    {
        $options[UrlConfigConstants::URL_TYPE] = $urlType;
        $options[UrlConfigConstants::PARAMS] = [$userId];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int $urlType
     * @param int|null $userId
     * @param array $options
     * @return static
     */
    public function forWeb(int $urlType, ?int $userId, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($urlType, $userId, $options);
    }

    /**
     * @param int $urlType
     * @param int|null $userId
     * @param array $options
     * @return static
     */
    public function forRedirect(int $urlType, ?int $userId, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($urlType, $userId, $options);
    }

    /**
     * @param int $urlType
     * @param int|null $userId
     * @param array $options
     * @return static
     */
    public function forDomainRule(int $urlType, ?int $userId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($urlType, $userId, $options);
    }

    /**
     * @param int $urlType
     * @param int|null $userId
     * @param array $options
     * @return static
     */
    public function forBackPage(int $urlType, ?int $userId, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($urlType, $userId, $options);
    }

    /**
     * @param int $urlType
     * @param array $options
     * @return static
     */
    public function forTemplateByType(int $urlType, array $options = []): static
    {
        $options = $this->toTemplateViewOptions($options);
        return $this->construct($urlType, null, $options);
    }

    // --- Local query methods ---

    /**
     * @return int|null
     */
    public function userId(): ?int
    {
        return $this->readIntParam(0);
    }
}
