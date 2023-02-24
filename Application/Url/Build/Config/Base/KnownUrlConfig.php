<?php
/**
 * Url config for case, when url path known in caller.
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
 * Class KnownUrlConfig
 * @package Sam\Application\Url
 */
class KnownUrlConfig extends AbstractUrlConfig
{
    /**
     * Any view url (relative url, full absolute or schemeless url)
     */
    private string $knownUrl = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $url
     * @param array $optionals
     * @return static
     */
    public function construct(string $url, array $optionals = []): static
    {
        $this->knownUrl = $url;
        return $this->fromArray($optionals);
    }

    /**
     * @param string $url
     * @param array $optionals
     * @return static
     */
    public function forWeb(string $url, array $optionals = []): static
    {
        $optionals = $this->toWebViewOptions($optionals);
        return $this->construct($url, $optionals);
    }

    /**
     * @param string $url
     * @param array $optionals
     * @return static
     */
    public function forRedirect(string $url, array $optionals = []): static
    {
        $optionals = $this->toRedirectViewOptions($optionals);
        return $this->construct($url, $optionals);
    }

    /**
     * @param string $url
     * @param array $optionals
     * @return static
     */
    public function forDomainRule(string $url, array $optionals = []): static
    {
        $optionals = $this->toDomainRuleViewOptions($optionals);
        return $this->construct($url, $optionals);
    }

    /**
     * @param string $url
     * @param array $optionals
     * @return static
     */
    public function forBackPage(string $url, array $optionals = []): static
    {
        $optionals = $this->toBackPageViewOptions($optionals);
        return $this->construct($url, $optionals);
    }

    /**
     * @override should return known url
     * @return string
     */
    public function urlTemplate(): string
    {
        return $this->knownUrl;
    }
}
