<?php
/**
 * SAM-7661: Settlement Printable & viewable version to the new layout Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-05, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Printable\Email;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Printable\Email\Css\EmailCssLoader;
use Sam\Settlement\Printable\PrintableSettlementRenderer;

/**
 * Class SettlementEmailPrintableService
 * @package Sam\Settlement\Printable\Email
 */
class SettlementEmailPrintableRenderer extends CustomizableClass
{
    /**
     * @var string
     *
     * Main wrapper css class at settlement email HTML
     * if you want to change it, you will need made related adjustments at email Css file,
     * located at wwwroot/css/settlement-email-print.css (make search by constant value)
     */
    public const MAIN_WRAPPER_CSS_CLASS = 'email-print-settlement';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $settlementId
     * @return string
     */
    public function render(int $settlementId): string
    {
        $printableSettlement = PrintableSettlementRenderer::new()->constructForEmail();
        $emailSettlementHtml = $printableSettlement->render([$settlementId]);

        $mainWrapperCssClass = self::MAIN_WRAPPER_CSS_CLASS;
        $emailSettlementHtml = <<<HTML
<div class="{$mainWrapperCssClass}">    
    {$emailSettlementHtml}
</div>
HTML;

        $cssRules = $this->getCssRules();

        /**
         * TODO. YV, 2021-03-07. We should move current email HTML 5 template generation to general email renderer.
         * I prefer to think of the current implementation as temporary or transient.
         * But current implementation works as expected too and we get properly rendered printable Email here.
         *
         * -- General statement --
         * All emails (for each email event by key @see \Sam\Core\Constants\EmailKey ), if we send them to clients as HTML,
         * should implement valid HTML 5 layout and email placeholders HTML should be implemented inside <body> tag only.
         *
         * If you look at $html variable content, you will see, that it includes two base parts:
         * 1st - is a HTML 5 layout wrapper,
         * 2nd - settlement Email printable HTML at $emailSettlementHtml variable placed inside <body> tag.
         * After described above implementation, expected output should contain only 2nd part here!
         */
        $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title></title>
  <style rel="stylesheet" type="text/css">{$cssRules}</style>
</head>
<body>
  {$emailSettlementHtml}
</body>
</html>
HTML;

        return $html;
    }

    /**
     * TODO: YV, 2021-03-07. These CSS rendering we can fetch at general Css rendering service like EmailCssRenderer::renderCssForSettlement
     * In case, where we need HTML in email, EmailCssRenderer will render CSS only for each email event by key @return string
     * @return string
     * @see \Sam\Core\Constants\EmailKey.
     * an output string we will use at email HTML <head> section as content of <style> tag.
     * ---
     * See example at  @see \Sam\Settlement\Printable\Email\SettlementEmailPrintableRenderer::render
     * for $html variable rendering (and my TODO for it).
     * @see \Sam\Settlement\Printable\Email\SettlementEmailPrintableRenderer::render
     *
     * Settlement email CSS rules.
     */
    protected function getCssRules(): string
    {
        $settlementCssLoader = EmailCssLoader::new();

        // Default settlement email CSS rules
        $defaultCss = $settlementCssLoader->loadCss();
        // Customized settlement CSS rules.
        $settlementCssCustomized = $settlementCssLoader->loadCustomizedCss();

        return $defaultCss . $settlementCssCustomized;
    }
}
