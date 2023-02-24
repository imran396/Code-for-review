<?php
/**
 * SAM-4586: Refactor CssLinksManager view helper to customized class
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/24/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Base\Render;

use Exception;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\ApplicationAwareTrait;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\View\Base\HtmlRenderer;

/**
 * Class CssLinksManager
 * @package Sam\View\Base\Render
 */
class CssLinksRenderer extends CustomizableClass
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
            $pathDetector = $this->createAssetPathDetector()->construct($this->cfg(), 'cssLinks');
        } catch (Exception $e) {
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

        $cssUrlPathsWithMTime = $this->buildCssPaths($urlPaths);
        return implode(" ", $cssUrlPathsWithMTime);
    }

    /**
     * @param array $urlPaths
     * @return array
     */
    private function buildCssPaths(array $urlPaths): array
    {
        $urlPathsWithMTime = [];
        $template = HtmlRenderer::new()->cssLink("%s");
        foreach ($urlPaths as $urlPath) {
            $urlPathsWithMTime[] = sprintf($template, $this->getFilePathHelper()->appendUrlPathWithMTime($urlPath));
        }
        return $urlPathsWithMTime;
    }
}
