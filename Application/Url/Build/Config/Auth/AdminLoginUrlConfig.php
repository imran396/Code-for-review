<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Auth;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Core\Constants;

/**
 * Class AdminLoginUrlConfig
 * @package Sam\Application\Acl
 */
class AdminLoginUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::A_ADMIN_LOGIN;

    /**
     * Class instantiation method
     * @return $this
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
