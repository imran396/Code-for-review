<?php
/**
 *
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

namespace Sam\Application\Url\Build\Config\Search;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Core\Constants;

/**
 * Class ZeroParamUrlConfig
 * @package Sam\Application\Url\Build\Config
 */
class ResponsiveSearchUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::P_SEARCH;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $options
     * @return static
     */
    public function construct(array $options = []): static
    {
        return $this->fromArray($options);
    }

    /**
     * @param array $options
     * @return static
     */
    public function forWeb(array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($options);
    }

    /**
     * @param array $options
     * @return static
     */
    public function forRedirect(array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($options);
    }

    /**
     * @param array $options
     * @return static
     */
    public function forDomainRule(array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($options);
    }

    /**
     * @param array $options
     * @return static
     */
    public function forBackPage(array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($options);
    }
}
