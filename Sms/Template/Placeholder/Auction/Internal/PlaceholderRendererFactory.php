<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\Auction\Internal;

use Sam\Core\Service\CustomizableClass;
use Sam\Sms\Template\Placeholder\Auction\Internal\Render\DatePlaceholderRenderer;
use Sam\Sms\Template\Placeholder\Auction\Internal\Render\NamePlaceholderRenderer;
use Sam\Sms\Template\Placeholder\Auction\Internal\Render\PlaceholderRendererInterface;
use Sam\Sms\Template\Placeholder\Auction\Internal\Render\SaleNoPlaceholderRenderer;
use Sam\Sms\Template\Placeholder\Auction\Internal\Render\SimplePlaceholderRenderer;
use Sam\Sms\Template\Placeholder\Auction\Internal\Render\TypePlaceholderRenderer;

/**
 * Class PlaceholderRendererFactory
 * @package Sam\Sms\Template\Placeholder\Auction\Internal
 * @internal
 */
class PlaceholderRendererFactory extends CustomizableClass
{
    protected const PLACEHOLDER_RENDER_CLASSES = [
        DatePlaceholderRenderer::class,
        NamePlaceholderRenderer::class,
        SaleNoPlaceholderRenderer::class,
        SimplePlaceholderRenderer::class,
        TypePlaceholderRenderer::class
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
