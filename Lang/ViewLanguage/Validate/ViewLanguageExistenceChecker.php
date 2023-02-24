<?php
/**
 * SAM-4675: View language loader and existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/5/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lang\ViewLanguage\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\ViewLanguage\ViewLanguageReadRepositoryCreateTrait;

/**
 * Class ViewLanguageExistenceChecker
 * @package Sam\Lang\ViewLanguage\Validate
 */
class ViewLanguageExistenceChecker extends CustomizableClass
{
    use ViewLanguageReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $id
     * @param int $accountId
     * @return bool
     */
    public function existByIdAndAccountId(int $id, int $accountId): bool
    {
        $isFound = $this->createViewLanguageReadRepository()
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->filterId($id)
            ->exist();
        return $isFound;
    }

    /**
     * @param string $name
     * @param int $accountId
     * @return bool
     */
    public function existByNameAndAccountId(string $name, int $accountId): bool
    {
        $isFound = $this->createViewLanguageReadRepository()
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->filterName($name)
            ->exist();
        return $isFound;
    }
}
