<?php
/**
 * SAM-11177: Stacked Tax. Invoice e-mail view - (Stage 2)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\View\IsolatedInvoiceView;

use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\View\IsolatedInvoiceView\Internal\IsolatedInvoiceViewModelFactoryCreateTrait;

/**
 * Class StackedTaxIsolatedInvoiceViewRenderer
 * @package Sam\Invoice\StackedTax\View\IsolatedInvoiceView
 */
class StackedTaxIsolatedInvoiceViewRenderer extends CustomizableClass
{
    use IsolatedInvoiceViewModelFactoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function render(int $invoiceId, int $languageId, string $templateName = 'default', array $templatePaths = []): string
    {
        $renderer = new PhpRenderer();
        $stack = new TemplatePathStack([
            'script_paths' => array_merge($templatePaths, [__DIR__ . '/template']),
        ]);
        $model = $this->createIsolatedInvoiceViewModelFactory()
            ->create($invoiceId, $languageId)
            ->setTemplate($templateName . '/index');

        $result = $renderer
            ->setResolver($stack)
            ->render($model);
        return $result;
    }
}
