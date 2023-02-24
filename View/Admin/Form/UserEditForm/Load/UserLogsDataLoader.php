<?php
/**
 * User Logs Data Loader
 *
 * SAM-6286: Refactor User Edit page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 14, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\UserEditForm\Load;

use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\ReadRepository\Entity\UserLog\UserLogReadRepository;
use Sam\Storage\ReadRepository\Entity\UserLog\UserLogReadRepositoryCreateTrait;

/**
 * Class UserLogsDataLoader
 */
class UserLogsDataLoader extends CustomizableClass
{
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;
    use UserAwareTrait;
    use UserLogReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int - return value of User Logs count
     */
    public function count(): int
    {
        return $this->prepareUserLogRepository()->count();
    }

    /**
     * @return array - return values for User Logs
     */
    public function load(): array
    {
        $repo = $this->prepareUserLogRepository();

        if ($this->getSortColumn() === 'note') {
            $repo->orderByNote($this->isAscendingOrder());
        } else {
            $repo->orderByTimeLog($this->isAscendingOrder());
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
     * @return UserLogReadRepository
     */
    protected function prepareUserLogRepository(): UserLogReadRepository
    {
        return $this->createUserLogReadRepository()
            ->filterUserId($this->getUserId());
    }
}
