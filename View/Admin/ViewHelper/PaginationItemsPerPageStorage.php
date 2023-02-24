<?php
/**
 * SAM-8004: Refactor \Util_Storage
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper;

use Sam\Application\UserDataStorage\UserDataStorageCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class PaginationItemsPerPageStorage
 * @package Sam\View\Admin\ViewHelper
 */
class PaginationItemsPerPageStorage extends CustomizableClass
{
    use UserDataStorageCreateTrait;

    private const STORAGE_KEY = 'NumPages';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Save number of pages in regular or cookie session
     *
     * @param int $itemsPerPage
     */
    public function set(int $itemsPerPage): void
    {
        $this->createUserDataStorage()->set(self::STORAGE_KEY, (string)$itemsPerPage);
    }

    /**
     * Get number of pages from regular or cookie session
     *
     * @return int
     */
    public function get(): int
    {
        $numPages = (int)$this->createUserDataStorage()->get(self::STORAGE_KEY, Constants\Page::$itemsPerPageNamesFullList['25']);
        return $numPages;
    }
}
