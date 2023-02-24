<?php
/**
 * SAM-4500: Front-end breadcrumb
 * https://bidpath.atlassian.net/browse/SAM-4500
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\ViewHelper\Breadcrumb\Internal\Build;

use Sam\Application\Language\Detect\ApplicationLanguageDetectorCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\View\Responsive\ViewHelper\Breadcrumb\Internal\Build\Internal\Collection;
use Sam\View\Responsive\ViewHelper\Breadcrumb\Internal\Build\Internal\CrumbBuilder;
use Sam\View\Responsive\ViewHelper\Breadcrumb\Internal\Build\Internal\PathMapper;

/**
 * Class CollectionBuilder
 * @package Sam\View\Responsive\ViewHelper\Breadcrumb\Internal\Build
 */
class CollectionBuilder extends CustomizableClass
{
    use ApplicationLanguageDetectorCreateTrait;
    use SystemAccountAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $controller
     * @param string $action
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @param int|null $id
     * @return Collection
     */
    public function build(string $controller, string $action, ?int $auctionId, ?int $lotItemId, ?int $id): Collection
    {
        $languageId = $this->createApplicationLanguageDetector()->detectActiveLanguageId();
        $caCollection = PathMapper::new()
            ->construct($this->getSystemAccountId(), $languageId)
            ->buildControllerActionToBreadcrumbMappings($lotItemId, $auctionId, $id);
        $collection = new Collection();
        $collection->add(CrumbBuilder::new()->buildHomeCrumb()); //add root

        if ($caCollection->has($controller, $action)) {
            $method = $caCollection->get($controller, $action);
            $crumbs = $method();
            foreach ($crumbs as $crumb) {
                $collection->add($crumb);
            }
        }
        return $collection;
    }
}
