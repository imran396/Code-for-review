<?php
/**
 * SAM-6489: Rtb console control rendering at server side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Control\Render;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class RtbControlRenderer
 * @package Sam\Rtb
 */
class RtbControlRenderer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $controlParams
     * @param array $customControlParams
     * @return string
     */
    public function render(array $controlParams, array $customControlParams): string
    {
        // prepare control params
        $controlParams = array_replace($controlParams, $customControlParams);

        // process rendering
        if ($controlParams['type'] === Constants\RtbConsole::CT_LINK) {
            return RtbControlByTypeRenderer::new()->renderLink($controlParams);
        }

        if ($controlParams['type'] === Constants\RtbConsole::CT_DIV) {
            return RtbControlByTypeRenderer::new()->renderDiv($controlParams);
        }

        if ($controlParams['type'] === Constants\RtbConsole::CT_SCRIPT) {
            return RtbControlByTypeRenderer::new()->renderScript($controlParams);
        }

        if ($controlParams['type'] === Constants\RtbConsole::CT_UL) {
            return RtbControlByTypeRenderer::new()->renderUl($controlParams);
        }

        if ($controlParams['type'] === Constants\RtbConsole::CT_TABLE) {
            return RtbControlByTypeRenderer::new()->renderTable($controlParams);
        }

        if ($controlParams['type'] === Constants\RtbConsole::CT_SPAN) {
            return RtbControlByTypeRenderer::new()->renderSpan($controlParams);
        }

        if ($controlParams['type'] === Constants\RtbConsole::CT_IMG) {
            return RtbControlByTypeRenderer::new()->renderImg($controlParams);
        }

        if ($controlParams['type'] === Constants\RtbConsole::CT_BUTTON) {
            return RtbControlByTypeRenderer::new()->renderButton($controlParams);
        }

        if ($controlParams['type'] === Constants\RtbConsole::CT_TEXT) {
            return RtbControlByTypeRenderer::new()->renderText($controlParams);
        }

        if ($controlParams['type'] === Constants\RtbConsole::CT_HIDDEN) {
            return RtbControlByTypeRenderer::new()->renderHidden($controlParams);
        }

        if ($controlParams['type'] === Constants\RtbConsole::CT_TEXTAREA) {
            return RtbControlByTypeRenderer::new()->renderTextarea($controlParams);
        }

        if ($controlParams['type'] === Constants\RtbConsole::CT_SELECT) {
            return RtbControlByTypeRenderer::new()->renderSelect($controlParams);
        }

        if ($controlParams['type'] === Constants\RtbConsole::CT_CHECKBOX) {
            return RtbControlByTypeRenderer::new()->renderCheckbox($controlParams);
        }

        if ($controlParams['type'] === Constants\RtbConsole::CT_IFRAME) {
            return RtbControlByTypeRenderer::new()->renderIframe($controlParams);
        }

        if ($controlParams['type'] === Constants\RtbConsole::CT_RADIO) {
            return RtbControlByTypeRenderer::new()->renderRadio($controlParams);
        }

        return '';
    }
}
