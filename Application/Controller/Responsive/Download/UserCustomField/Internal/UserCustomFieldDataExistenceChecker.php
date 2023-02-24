<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Download\UserCustomField\Internal;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\UserCustField\UserCustFieldReadRepositoryCreateTrait;

/**
 * Class UserCustomFieldDataExistenceChecker
 * @package Sam\Application\Controller\Responsive\Download\Internal
 */
class UserCustomFieldDataExistenceChecker extends CustomizableClass
{
    use UserCustFieldReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function existWithFile(string $fileName, bool $isReadOnlyDb = false): bool
    {
        return $this->createUserCustFieldReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterType(Constants\CustomField::TYPE_FILE)
            ->joinUserCustDataFilterActive(true)
            ->joinUserCustDataLikeText('%' . $fileName . '%')
            ->exist();
    }
}
