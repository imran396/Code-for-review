<?php
/**
 * SAM-7661: Settlement Printable & viewable version to the new layout Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-24, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Printable\Email\Css;

use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementEmailCssLoader
 * @package Sam\Settlement\Printable\Email
 */
class EmailCssLoader extends CustomizableClass
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
     * Load default CSS styles for settlement email.
     * Css file location: /wwwroot/css/settlement-email-print.css
     * @return string
     */
    public function loadCss(): string
    {
        return $this->fetchCssFile();
    }

    /**
     * Load customizable CSS styles for settlement email.
     * Css file location: /wwwroot/css/custom/settlement-email-print-custom.css
     * @return string
     */
    public function loadCustomizedCss(): string
    {
        return $this->fetchCssFile(true);
    }

    /**
     * @param bool $isLoadCustomCss
     * @return string
     */
    protected function fetchCssFile(bool $isLoadCustomCss = false): string
    {
        $docRootPath = path()->docRoot();
        $cssFileName = 'settlement-email-print.css';
        $cssFilePath = $docRootPath . "/css/{$cssFileName}";
        if ($isLoadCustomCss) {
            $cssFileName = 'settlement-email-print-custom.css';
            // YV, SAM-7661, 26.03.2021: Frontend devs have (write) access only for /wwwroot/css/custom folder
            // /wwwroot/css/custom - does not exists at version repository but it exists at live installations.
            // And it confused, because version repository structure should be the same with live installations.
            $cssFilePath = $docRootPath . "/css/custom/{$cssFileName}";
        }

        $loadedCss = @file_get_contents($cssFilePath);
        if ($loadedCss === false) {
            $message = $isLoadCustomCss
                ? 'Unable to load customized CSS styles file for settlement email! '
                : 'Unable to load default CSS styles file for settlement email! ';
            log_error($message . composeLogData(['file' => $cssFilePath]));
            return '';
        }

        return $loadedCss;
    }
}
