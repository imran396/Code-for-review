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
class ResponsiveLegacyMvc extends CustomizableClass
{
    use LegacyMvcCreateTrait;

    private const LAYOUT_PLAIN = 'plain';
    private const LAYOUT_MOBILE_PRINT = 'mobile-print';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Layout ---

    public function setPlainLayout(array $variables = []): static
    {
        $this->createLegacyMvc()->setLayout(self::LAYOUT_PLAIN, $variables);
        return $this;
    }

    public function setMobilePrintLayout(array $variables = []): static
    {
        $this->createLegacyMvc()->setLayout(self::LAYOUT_MOBILE_PRINT, $variables);
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
