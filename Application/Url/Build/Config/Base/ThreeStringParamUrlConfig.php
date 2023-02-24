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

namespace Sam\Application\Url\Build\Config\Base;

/**
 * @package Sam\Application\Url
 */
class ThreeStringParamUrlConfig extends AbstractUrlConfig
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Constructors ---

    /**
     * @param int $urlType
     * @param string|null $param1
     * @param string|null $param2
     * @param string|null $param3
     * @param array $options = [
     *     ... // regular options
     *     ... // account pre-loading optionals (JIC, not needed now)
     * ]
     * @return static
     */
    public function construct(int $urlType, ?string $param1, ?string $param2, ?string $param3, array $options = []): static
    {
        $options[UrlConfigConstants::URL_TYPE] = $urlType;
        $options[UrlConfigConstants::PARAMS] = [$param1, $param2, $param3];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int $urlType
     * @param string|null $param1
     * @param string|null $param2
     * @param string|null $param3
     * @param array $options
     * @return static
     */
    public function forWeb(int $urlType, ?string $param1, ?string $param2, ?string $param3, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($urlType, $param1, $param2, $param3, $options);
    }

    /**
     * @param int $urlType
     * @param string|null $param1
     * @param string|null $param2
     * @param string|null $param3
     * @param array $options
     * @return static
     */
    public function forRedirect(int $urlType, ?string $param1, ?string $param2, ?string $param3, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($urlType, $param1, $param2, $param3, $options);
    }

    /**
     * @param int $urlType
     * @param string|null $param1
     * @param string|null $param2
     * @param string|null $param3
     * @param array $options
     * @return static
     */
    public function forDomainRule(int $urlType, ?string $param1, ?string $param2, ?string $param3, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($urlType, $param1, $param2, $param3, $options);
    }

    /**
     * @param int $urlType
     * @param string|null $param1
     * @param string|null $param2
     * @param string|null $param3
     * @param array $options
     * @return static
     */
    public function forBackPage(int $urlType, ?string $param1, ?string $param2, ?string $param3, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($urlType, $param1, $param2, $param3, $options);
    }

    /**
     * @param int $urlType
     * @param array $options
     * @return static
     */
    public function forTemplateByType(int $urlType, array $options = []): static
    {
        $options = $this->toTemplateViewOptions($options);
        return $this->construct($urlType, null, null, null, $options);
    }

    // --- Local query methods ---

    /**
     * @return string|null
     */
    public function firstParam(): ?string
    {
        return $this->readStringParam(0);
    }

    /**
     * @return string|null
     */
    public function secondParam(): ?string
    {
        return $this->readStringParam(1);
    }

    /**
     * @return string|null
     */
    public function thirdParam(): ?string
    {
        return $this->readStringParam(2);
    }
}
