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

namespace Sam\Sms\Template\Placeholder\LotItem;

use InvalidArgumentException;
use LotItem;
use Sam\Core\Service\CustomizableClass;
use Sam\Sms\Template\Placeholder\LotItem\Internal\PlaceholderRendererFactoryCreateTrait;
use Sam\Sms\Template\Placeholder\LotItem\Internal\Render\PlaceholderRendererInterface;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholderRendererInterface;

/**
 * Class LotItemPlaceholderRenderer
 * @package Sam\Sms\Template\Placeholder\LotItem
 */
class LotItemPlaceholderRenderer extends CustomizableClass implements SmsTemplatePlaceholderRendererInterface
{
    use PlaceholderRendererFactoryCreateTrait;

    protected LotItem $lotItem;
    /**
     * @var PlaceholderRendererInterface[]
     */
    protected array $placeholderRendererMap;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(LotItem $lotItem): static
    {
        $this->lotItem = $lotItem;
        $renderers = $this->createPlaceholderRendererFactory()->createRenderers();
        $this->placeholderRendererMap = $this->makePlaceholderRendererMap($renderers);
        return $this;
    }

    public function getApplicablePlaceholderKeys(): array
    {
        $applicablePlaceholders = array_keys($this->placeholderRendererMap);
        return $applicablePlaceholders;
    }

    public function render(SmsTemplatePlaceholder $placeholder): string
    {
        $renderer = $this->getPlaceholderRenderer($placeholder->key);
        return $renderer->render($placeholder, $this->lotItem);
    }

    protected function getPlaceholderRenderer(string $placeholderKey): PlaceholderRendererInterface
    {
        if (!array_key_exists($placeholderKey, $this->placeholderRendererMap)) {
            throw new InvalidArgumentException("Not applicable placeholder '{$placeholderKey}'");
        }
        return $this->placeholderRendererMap[$placeholderKey];
    }

    /**
     * @param PlaceholderRendererInterface[] $renderers
     * @return PlaceholderRendererInterface[]
     */
    protected function makePlaceholderRendererMap(array $renderers): array
    {
        $map = [];
        foreach ($renderers as $renderer) {
            $applicablePlaceholders = $renderer->getApplicablePlaceholderKeys();
            foreach ($applicablePlaceholders as $placeholder) {
                $map[$placeholder] = $renderer;
            }
        }
        return $map;
    }
}
