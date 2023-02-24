<?php
/**
 * Render language select box
 *
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Render;

use Sam\Application\Language\Detect\ApplicationLanguageDetectorCreateTrait;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lang\ViewLanguage\Load\ViewLanguageLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\View\Base\HtmlRenderer;

/**
 * Class Translations
 * @package Sam\View\Responsive\Render
 */
class Translations extends CustomizableClass
{
    use ApplicationLanguageDetectorCreateTrait;
    use SystemAccountAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;
    use ViewLanguageLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $languages = $this->getViewLanguageLoader()->loadByAccountId($this->getSystemAccountId());
        if (count($languages)) {
            $languageUrl = $this->getUrlBuilder()->build(
                ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_LANGUAGE)
            );
            $activeLanguageId = $this->createApplicationLanguageDetector()->detectActiveLanguageId();
            $templateData = [
                'activeLanguageId' => $activeLanguageId,
                'defaultLanguage' => $this->getTranslator()->translate('GENERAL_LANGUAGE_DEFAULT', 'general'),
                'languages' => $languages,
                'url' => $languageUrl,
            ];
            $output = HtmlRenderer::new()->getTemplate(
                'auctions/language-select.tpl.php',
                $templateData,
                Ui::new()->constructWebResponsive()
            );

            return $output;
        }

        return '';
    }
}
