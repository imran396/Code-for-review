<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           02/06/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\EditBlock;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Render\EditBlock\InfoColumn\InfoColumnRenderer;
use Sam\Installation\Config\Edit\Render\EditBlock\EditComponent\EditComponentRenderer;

/**
 * Class EditBlockRenderer
 * @package Sam\Installation\Config
 * @author: Yura Vakulenko
 */
class EditBlockRenderer extends CustomizableClass
{
    use OptionKeyAwareTrait;
    use InputDataWebAwareTrait;

    protected const HTML_TMPL = '<div class="row align-items-start no-gutters">%s</div>';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build valid input html for different input types
     * (radio buttons, regular inputs, textarea, checkboxes)
     *
     * @param string $configName
     * @return string
     */
    public function render(string $configName): string
    {
        $infoColumnHtml = InfoColumnRenderer::new()
            ->construct($this->getInputDataWeb())
            ->render();
        $mainColumnHtml = EditComponentRenderer::new()
            ->construct($this->getInputDataWeb(), $this->getOptionKey())
            ->render($configName);
        $output = sprintf(self::HTML_TMPL, $infoColumnHtml . $mainColumnHtml);
        return $output;
    }
}
