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

namespace Sam\Sms\Template\Placeholder\LotItem\Internal;

use Sam\Core\Service\CustomizableClass;
use Sam\Sms\Template\Placeholder\LotItem\Internal\Render\CategoriesPlaceholderRenderer;
use Sam\Sms\Template\Placeholder\LotItem\Internal\Render\ConsignorPlaceholderRenderer;
use Sam\Sms\Template\Placeholder\LotItem\Internal\Render\CustomFieldPlaceholderRenderer;
use Sam\Sms\Template\Placeholder\LotItem\Internal\Render\DateSoldPlaceholderRenderer;
use Sam\Sms\Template\Placeholder\LotItem\Internal\Render\ImagesPlaceholderRenderer;
use Sam\Sms\Template\Placeholder\LotItem\Internal\Render\NoPlaceholderRenderer;
use Sam\Sms\Template\Placeholder\LotItem\Internal\Render\PlaceholderRendererInterface;
use Sam\Sms\Template\Placeholder\LotItem\Internal\Render\SaleSoldPlaceholderRenderer;
use Sam\Sms\Template\Placeholder\LotItem\Internal\Render\SimplePlaceholderRenderer;

/**
 * Class PlaceholderRendererFactory
 * @package Sam\Sms\Template\Placeholder\LotItem\Internal
 * @internal
 */
class PlaceholderRendererFactory extends CustomizableClass
{
    protected const PLACEHOLDER_RENDER_CLASSES = [
        CategoriesPlaceholderRenderer::class,
        ConsignorPlaceholderRenderer::class,
        CustomFieldPlaceholderRenderer::class,
        DateSoldPlaceholderRenderer::class,
        ImagesPlaceholderRenderer::class,
        NoPlaceholderRenderer::class,
        SaleSoldPlaceholderRenderer::class,
        SimplePlaceholderRenderer::class,
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
