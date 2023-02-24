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
 * Class ZeroParamUrlConfig
 * @package Sam\Application\Url\Build\Config
 */
class ZeroParamUrlConfig extends AbstractUrlConfig
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $urlType
     * @param array $options
     * @return static
     */
    public function construct(int $urlType, array $options = []): static
    {
        $options[UrlConfigConstants::URL_TYPE] = $urlType;
        return $this->fromArray($options);
    }

    /**
     * @param int $urlType
     * @param array $options
     * @return static
     */
    public function forWeb(int $urlType, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($urlType, $options);
    }

    /**
     * @param int $urlType
     * @param array $options
     * @return static
     */
    public function forRedirect(int $urlType, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($urlType, $options);
    }

    /**
     * @param int $urlType
     * @param array $options
     * @return static
     */
    public function forDomainRule(int $urlType, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($urlType, $options);
    }

    /**
     * @param int $urlType
     * @param array $options
     * @return static
     */
    public function forBackPage(int $urlType, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($urlType, $options);
    }

// We don't have this method, because zero param urls don't need to be rendered in template view
//
//    public function forTemplateByType(int $urlType, array $options = []): static
//    {
//        $options = $this->toTemplateViewOptions($options);
//        return $this->construct($urlType, $options);
//    }

}
