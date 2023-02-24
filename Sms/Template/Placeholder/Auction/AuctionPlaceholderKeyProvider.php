<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\Auction;

use Sam\Core\Service\CustomizableClass;
use Sam\Sms\Template\Placeholder\Auction\Internal\PlaceholderRendererFactoryCreateTrait;
use Sam\Sms\Template\Placeholder\Auction\Internal\Render\PlaceholderRendererInterface;

/**
 * Class AuctionPlaceholderKeyProvider
 * @package Sam\Sms\Template\Placeholder\Auction
 */
class AuctionPlaceholderKeyProvider extends CustomizableClass
{
    use PlaceholderRendererFactoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getKeys(): array
    {
        $renderers = $this->createPlaceholderRendererFactory()->createRenderers();
        $keys = array_map(
            static function (PlaceholderRendererInterface $renderer) {
                return $renderer->getApplicablePlaceholderKeys();
            },
            $renderers
        );
        return array_merge(...$keys);
    }
}
