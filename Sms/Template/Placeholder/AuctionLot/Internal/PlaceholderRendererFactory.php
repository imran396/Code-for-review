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

namespace Sam\Sms\Template\Placeholder\AuctionLot\Internal;

use Sam\Core\Service\CustomizableClass;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\AskingBidPlaceholderRenderer;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\BidQuantityPlaceholderRenderer;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\CurrentBidPlaceholderRenderer;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\DetailsUrlPlaceholderRenderer;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\EndDatePlaceholderRenderer;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\NamePlaceholderRenderer;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\NoPlaceholderRenderer;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\PlaceholderRendererInterface;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\QuantityPlaceholderRenderer;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\SimplePlaceholderRenderer;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\StartDatePlaceholderRenderer;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\StatusPlaceholderRenderer;
use Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\TimeLeftPlaceholderRenderer;

/**
 * Class PlaceholderRendererFactory
 * @package Sam\Sms\Template\Placeholder\AuctionLot
 * @internal
 */
class PlaceholderRendererFactory extends CustomizableClass
{
    protected const PLACEHOLDER_RENDER_CLASSES = [
        AskingBidPlaceholderRenderer::class,
        BidQuantityPlaceholderRenderer::class,
        CurrentBidPlaceholderRenderer::class,
        DetailsUrlPlaceholderRenderer::class,
        EndDatePlaceholderRenderer::class,
        NamePlaceholderRenderer::class,
        NoPlaceholderRenderer::class,
        QuantityPlaceholderRenderer::class,
        SimplePlaceholderRenderer::class,
        StartDatePlaceholderRenderer::class,
        StatusPlaceholderRenderer::class,
        TimeLeftPlaceholderRenderer::class,
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return PlaceholderRendererInterface[]
     */
    public function createRenderers(): array
    {
        return array_map([$this, 'createRendererObject'], self::PLACEHOLDER_RENDER_CLASSES);
    }

    protected function createRendererObject(string $className): PlaceholderRendererInterface
    {
        return call_user_func([$className, 'new']);
    }
}
