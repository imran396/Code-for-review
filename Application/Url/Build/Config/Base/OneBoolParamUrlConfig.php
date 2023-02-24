<?php
/**
 * SAM-11612: Tech support tool to easily and temporarily disable installation look and feel customizations
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Base;

class OneBoolParamUrlConfig extends AbstractUrlConfig
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
     * @param bool $param1
     * @param array $options
     * @return static
     */
    public function construct(int $urlType, bool $param1, array $options = []): static
    {
        $options[UrlConfigConstants::URL_TYPE] = $urlType;
        $options[UrlConfigConstants::PARAMS] = [$param1];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int $urlType
     * @param bool $param1
     * @param array $options
     * @return static
     */
    public function forWeb(int $urlType, bool $param1, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($urlType, $param1, $options);
    }

    /**
     * @param int $urlType
     * @param bool $param1
     * @param array $options
     * @return static
     */
    public function forRedirect(int $urlType, bool $param1, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($urlType, $param1, $options);
    }

    /**
     * @param int $urlType
     * @param bool $param1
     * @param array $options
     * @return static
     */
    public function forDomainRule(int $urlType, bool $param1, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($urlType, $param1, $options);
    }

    /**
     * @param int $urlType
     * @param bool $param1
     * @param array $options
     * @return static
     */
    public function forBackPage(int $urlType, bool $param1, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($urlType, $param1, $options);
    }
}
