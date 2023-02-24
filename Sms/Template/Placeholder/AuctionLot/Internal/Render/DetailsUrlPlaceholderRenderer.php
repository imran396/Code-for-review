<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render;

use AuctionLotItem;
use InvalidArgumentException;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\PlaceholderKey;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;

/**
 * Class DetailsUrlPlaceholderRenderer
 * @package Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render
 * @internal
 */
class DetailsUrlPlaceholderRenderer extends CustomizableClass implements PlaceholderRendererInterface
{
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getApplicablePlaceholderKeys(): array
    {
        return [PlaceholderKey::DETAILS_URL, PlaceholderKey::DETAILS_URL_WITHOUT_SEO];
    }

    public function render(SmsTemplatePlaceholder $placeholder, AuctionLotItem $auctionLot): string
    {
        if (!in_array($placeholder->key, $this->getApplicablePlaceholderKeys(), true)) {
            throw new InvalidArgumentException("Not applicable placeholder '{$placeholder->key}'");
        }

        $seoUrl = $placeholder->key === PlaceholderKey::DETAILS_URL_WITHOUT_SEO ? '' : null;
        $url = $this->getUrlBuilder()->build(
            ResponsiveLotDetailsUrlConfig::new()->forDomainRule(
                $auctionLot->LotItemId,
                $auctionLot->AuctionId,
                $seoUrl,
                [UrlConfigConstants::OP_ACCOUNT_ID => $auctionLot->AccountId]
            )
        );
        return $url;
    }
}
