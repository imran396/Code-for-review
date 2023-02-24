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

namespace Sam\Sms\Template\Placeholder\Auction\Internal\Render;

use Auction;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Sms\Template\Placeholder\Auction\Internal\PlaceholderKey;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;

/**
 * Class NamePlaceholderRenderer
 * @package Sam\Sms\Template\Placeholder\Auction\Internal\Render
 * @internal
 */
class NamePlaceholderRenderer extends CustomizableClass implements PlaceholderRendererInterface
{
    use AuctionRendererAwareTrait;

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
        return [
            PlaceholderKey::NAME,
        ];
    }

    public function render(SmsTemplatePlaceholder $placeholder, Auction $auction): string
    {
        if ($placeholder->key !== PlaceholderKey::NAME) {
            throw new \InvalidArgumentException("Not applicable placeholder '{$placeholder->key}'");
        }

        return $this->getAuctionRenderer()->renderName($auction);
    }
}
