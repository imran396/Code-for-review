<?php
/**
 * Url config immutable VO for configuring account image url rendering.
 *
 * All image urls are rendered with help of Domain Rule view (core->app->url->domainRule) in result, so we don't need other constructors.
 * Parent class supply possibility to build url template view.
 * When we want base file path, we can call urlFilled().
 *
 * SAM-6695: Image link prefix detection do not provide default value and are not based on account of context
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

namespace Sam\Application\Url\Build\Config\Image;

use Sam\Application\Url\Build\Config\Image\Base\AbstractImageUrlConfig;
use Sam\Core\Constants;

/**
 * Class LotItemImageUrlConfig
 * @package Sam\Application\Url
 */
class CaptchaImageUrlConfig extends AbstractImageUrlConfig
{
    protected ?int $urlType = Constants\Url::I_CAPTCHA;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Captcha image url should be build for system account,
     * because this dynamic image building operates with the session of the visiting domain.
     *
     * @param array $options = [
     *     ... // regular options
     * ]
     * @return static
     */
    public function construct(array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        $this->initOptionalAccount($options);
        $this->fromArray($options);
        return $this;
    }

    /**
     * Account is not needed for captcha dynamically generated image.
     * Captcha url should be addressed by running system domain account.
     * @return int|null
     */
    public function accountId(): ?int
    {
        return null;
    }
}
