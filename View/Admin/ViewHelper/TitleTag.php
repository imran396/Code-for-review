<?php
/**
 * Render title tag
 *
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 3, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper;

use Sam\Core\Service\CustomizableClass;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\View\Base\HtmlRenderer;
use Sam\Core\Constants;

/**
 * Class TitleTag
 * @package Sam\View\Admin\ViewHelper
 */
class TitleTag extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
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
        $paramFetcherForRoute = $this->getParamFetcherForRoute();
        $parts = [
            $paramFetcherForRoute->getActionName(),
            $paramFetcherForRoute->getControllerName(),
            Constants\Application::UIDIR_ADMIN,
            $this->cfg()->get('core->app->rolloutName'),
            'SAM - Smarter Auction Management'
        ];
        $parts = array_filter(
            $parts,
            static function ($arg) {
                return !in_array($arg, ['', Constants\AdminRoute::DEFAULT_ACTION], true);
            }
        );
        $output = implode(' - ', $parts);
        $output = HtmlRenderer::new()->title($output);
        return $output;
    }
}
