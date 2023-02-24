<?php
/**
 * Url config immutable VO for configuring auction image url rendering.
 *
 * All image urls are rendered with help of Domain Rule view (core->app->url->domainRule) in result, so we don't need other constructors.
 * Parent class supply possibility to build url template view.
 * When we want base file path, we can call urlFilled(). Account id not needed then.
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
 * Class AuctionImageUrlConfig
 * @package Sam\Application\Url
 */
class AuctionImageUrlConfig extends AbstractImageUrlConfig
{
    protected ?int $urlType = Constants\Url::I_AUCTION;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $auctionImageId - pass null when constructing template url for js.
     * @param string|null $size - pass null when constructing template url for js. (!) null doesn't mean default.
     * @param int|null $accountId - we don't need to know account when building template url for js or when fetch urlFilled().
     * @param array $options = [
     *     ... // regular options
     * ]
     * @return static
     */
    public function construct(?int $auctionImageId, ?string $size, ?int $accountId = null, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        $options[UrlConfigConstants::PARAMS] = [$auctionImageId, $size];
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
        return $this->getOptionalAccountId();
    }

    /**
     * Auction image id
     * @return int|null
     */
    public function auctionImageId(): ?int
    {
        return $this->readIntParam(0);
    }

    /**
     * Thumbnail image size
     * @return string
     */
    public function size(): string
    {
        return (string)$this->readStringParam(1);
    }
}
