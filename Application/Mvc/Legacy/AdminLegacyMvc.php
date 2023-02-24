<?php
/**
 * SAM-10438: Wrap ZF MVC functions
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Mvc\Legacy;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LegacyMvc
 * @package Sam\Application\Mvc\Legacy
 */
class AdminLegacyMvc extends CustomizableClass
{
    use LegacyMvcCreateTrait;

    private const LAYOUT_BLANK = 'blank';
    private const LAYOUT_DEFAULT = 'default';
    private const LAYOUT_MOBILE_PRINT = 'mobile-print';
    private const LAYOUT_POPUP = 'popup';
    private const LAYOUT_PRINT = 'print';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Layout ---

    public function setBlankLayout(array $variables = []): static
    {
        $this->createLegacyMvc()->setLayout(self::LAYOUT_BLANK, $variables);
        return $this;
    }

    public function setDefaultLayout(array $variables = []): static
    {
        $this->createLegacyMvc()->setLayout(self::LAYOUT_DEFAULT, $variables);
        return $this;
    }

    public function setMobilePrintLayout(array $variables = []): static
    {
        $this->createLegacyMvc()->setLayout(self::LAYOUT_MOBILE_PRINT, $variables);
        return $this;
    }

    public function setPopupLayout(array $variables = []): static
    {
        $this->createLegacyMvc()->setLayout(self::LAYOUT_POPUP, $variables);
        return $this;
    }

    public function setPrintLayout(array $variables = []): static
    {
        $this->createLegacyMvc()->setLayout(self::LAYOUT_PRINT, $variables);
        return $this;
    }

    // --- Rendering ---

    public function setScriptAction(string $name): static
    {
        $this->createLegacyMvc()->setScriptAction($name);
        return $this;
    }

    public function disableRendering(): static
    {
        $this->createLegacyMvc()->disableRendering();
        return $this;
    }

    public function disableLayout(): static
    {
        $this->createLegacyMvc()->disableLayout();
        return $this;
    }

    public function assign(array $variables): static
    {
        $this->createLegacyMvc()->assign($variables);
        return $this;
    }

    public function assignDraftContent(string $formClass): static
    {
        $this->createLegacyMvc()->assignDraftContent($formClass);
        return $this;
    }

    public function sendJson(mixed $response): static
    {
        $this->createLegacyMvc()->sendJson($response);
        return $this;
    }

    public function renderView(string $name): string
    {
        return $this->createLegacyMvc()->renderView($name);
    }

    public function getViewSuffix(): string
    {
        return $this->createLegacyMvc()->getViewSuffix();
    }
}
