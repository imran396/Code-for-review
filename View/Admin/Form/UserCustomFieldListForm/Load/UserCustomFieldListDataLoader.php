<?php
/**
 * User Custom Field List Data Loader
 *
 * SAM-6285: Refactor User Custom Field List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 13, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\UserCustomFieldListForm\Load;

use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\UserCustField\UserCustFieldReadRepository;
use Sam\Storage\ReadRepository\Entity\UserCustField\UserCustFieldReadRepositoryCreateTrait;
use Sam\View\Admin\Form\UserCustomFieldListForm\UserCustomFieldListConstants;

/**
 * Class UserCustomFieldListDataLoader
 */
class UserCustomFieldListDataLoader extends CustomizableClass
{
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;
    use UserCustFieldReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int - return value of User Custom Fields count
     */
    public function count(): int
    {
        return $this->prepareUserCustFieldRepository()->count();
    }

    /**
     * @return array - return values for User Custom Fields
     */
    public function load(): array
    {
        $repo = $this->prepareUserCustFieldRepository();

        switch ($this->getSortColumn()) {
            case UserCustomFieldListConstants::ORD_NAME:
                $repo->orderByName($this->isAscendingOrder());
                break;
            case UserCustomFieldListConstants::ORD_ORDER:
                $repo->orderByOrder($this->isAscendingOrder());
                break;
            case UserCustomFieldListConstants::ORD_TYPE:
                $repo->orderByType($this->isAscendingOrder());
                break;
            case UserCustomFieldListConstants::ORD_PANEL:
                $repo->orderByPanel($this->isAscendingOrder());
                break;
            case UserCustomFieldListConstants::ORD_REQUIRED:
                $repo->orderByRequired($this->isAscendingOrder());
                break;
            case UserCustomFieldListConstants::ORD_ENCRYPTED:
                $repo->orderByEncrypted($this->isAscendingOrder());
                break;
            case UserCustomFieldListConstants::ORD_ON_REGISTRATION:
                $repo->orderByOnRegistration($this->isAscendingOrder());
                break;
            case UserCustomFieldListConstants::ORD_ON_PROFILE:
                $repo->orderByOnProfile($this->isAscendingOrder());
                break;
            case UserCustomFieldListConstants::ORD_PARAMETERS:
                $repo->orderByParameters($this->isAscendingOrder());
                break;
        }

        if ($this->getOffset()) {
            $repo->offset($this->getOffset());
        }

        if ($this->getLimit()) {
            $repo->limit($this->getLimit());
        }

        return $repo->loadEntities();
    }

    /**
     * @return UserCustFieldReadRepository
     */
    protected function prepareUserCustFieldRepository(): UserCustFieldReadRepository
    {
        return $this->createUserCustFieldReadRepository()
            ->filterActive(true);
    }
}
