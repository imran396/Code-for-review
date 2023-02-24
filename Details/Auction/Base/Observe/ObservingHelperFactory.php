<?php
/**
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           4/6/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Base\Observe;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class ObservingHelperFactory
 * @package
 */
class ObservingHelperFactory extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function create(): ObservingHelper
    {
        $observingPropertyManagers = [];
        if ($this->cfg()->get('core->auction->seoUrl->enabled')) {
            $observingPropertyManagers[] = \Sam\Details\Auction\SeoUrl\Observe\ObservingPropertyManager::new();
        }
        $observingPropertyManagers[] = \Sam\Details\Auction\Web\Caption\Observe\ObservingPropertyManager::new();
        return ObservingHelper::new()->construct($observingPropertyManagers);
    }
}
