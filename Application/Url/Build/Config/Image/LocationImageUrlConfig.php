<?php
/**
 * Url config immutable VO for configuring location image url rendering.
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

use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\Config\Image\Base\AbstractImageUrlConfig;
use Sam\Core\Constants;

/**
 * Class LocationImageUrlConfig
 * @package Sam\Application\Url
 */
class LocationImageUrlConfig extends AbstractImageUrlConfig
{
    protected ?int $urlType = Constants\Url::I_LOCATION;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $locationId - pass null when constructing template url for js
     * @param int|null $accountId - pass null when constructing template url for js
     * @param array $options = [
     *     ... // regular options
     * ]
     * @return static
     */
    public function construct(?int $locationId, ?int $accountId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        $options[UrlConfigConstants::PARAMS] = [$accountId, $locationId];
        $options[UrlConfigConstants::OP_ACCOUNT_ID] = $accountId;
        $this->initOptionalAccount($options);
        $this->fromArray($options);
        return $this;
    }

    /**
     * @return int|null
     */
    public function accountId(): ?int
    {
        return $this->readIntParam(0);
    }

    /**
     * @return int|null
     */
    public function locationId(): ?int
    {
        return $this->readIntParam(1);
    }
}
