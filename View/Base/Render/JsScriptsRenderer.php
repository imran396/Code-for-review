<?php
/**
 * Render specific scripts for pages
 *
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 15, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Base\Render;

use Sam\Core\Service\CustomizableClass;
use Sam\Application\ApplicationAwareTrait;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\View\Base\HtmlRenderer;

/**
 * Class JsScriptsManager
 * @package Sam\View\Base\Render
 */
class JsScriptsRenderer extends CustomizableClass
{
    use ApplicationAwareTrait;
    use AssetPathDetectorCreateTrait;
    use ConfigRepositoryAwareTrait;
    use FilePathHelperAwareTrait;
    use ParamFetcherForRouteAwareTrait;

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
        try {
            $pathDetector = $this->createAssetPathDetector()->construct($this->cfg(), 'jsScripts');
        } catch (\Exception $e) {
            log_error($e->getMessage() . composeSuffix(['code' => $e->getCode()]));
            return '';
        }
        $paramFetcherForRoute = $this->getParamFetcherForRoute();
        $urlPaths = $pathDetector->detect(
            $this->getApplication()->ui(),
            $paramFetcherForRoute->getControllerName(),
            $paramFetcherForRoute->getActionName()
        );
        if (!$urlPaths) {
            return '';
        }

        $scriptUrlPathsWithMTime = $this->buildJsPaths($urlPaths);
        return implode('', $scriptUrlPathsWithMTime);
    }

    /**
     * @param array $urlPaths
     * @return array
     */
    private function buildJsPaths(array $urlPaths): array
    {
        $output = [];
        foreach ($urlPaths as $urlPath) {
            $urlPathWithMTime = $this->getFilePathHelper()->appendUrlPathWithMTime($urlPath);
            $output[] = HtmlRenderer::new()->script($urlPathWithMTime);
        }
        return $output;
    }
}
