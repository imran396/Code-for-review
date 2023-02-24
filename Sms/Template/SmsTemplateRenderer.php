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

namespace Sam\Sms\Template;

use Sam\Core\Service\CustomizableClass;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholderDetectorCreateTrait;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholderRendererInterface;

/**
 * Class SmsTemplateRenderer
 * @package Sam\Sms\Template
 */
class SmsTemplateRenderer extends CustomizableClass
{
    use SmsTemplatePlaceholderDetectorCreateTrait;

    /**
     * @var SmsTemplatePlaceholderRendererInterface[]
     */
    protected array $placeholderRendererMap = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param SmsTemplatePlaceholderRendererInterface[] $placeholderRenderers
     * @return static
     */
    public function construct(array $placeholderRenderers): static
    {
        $this->placeholderRendererMap = $this->makePlaceholderRendererMap($placeholderRenderers);
        return $this;
    }

    public function render(string $template): string
    {
        $placeholderDetector = $this->createSmsTemplatePlaceholderDetector();
        $placeholders = $placeholderDetector->detectAllPlaceholders($template);
        $output = $template;
        foreach ($placeholders as $placeholder) {
            $renderer = $this->findPlaceholderRenderer($placeholder);
            if ($renderer) {
                $output = str_replace($placeholder->view, $renderer->render($placeholder), $output);
            }
        }
        return $output;
    }

    protected function findPlaceholderRenderer(SmsTemplatePlaceholder $placeholder): ?SmsTemplatePlaceholderRendererInterface
    {
        return $this->placeholderRendererMap[$placeholder->key] ?? null;
    }

    /**
     * @param SmsTemplatePlaceholderRendererInterface[] $placeholderRenderers
     * @return array
     */
    protected function makePlaceholderRendererMap(array $placeholderRenderers): array
    {
        $map = [];
        foreach ($placeholderRenderers as $renderer) {
            $applicablePlaceholders = $renderer->getApplicablePlaceholderKeys();
            foreach ($applicablePlaceholders as $placeholder) {
                $map[$placeholder] = $renderer;
            }
        }
        return $map;
    }
}
