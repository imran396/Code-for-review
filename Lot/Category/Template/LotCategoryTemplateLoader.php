<?php
/**
 * Help methods for Lot Category template loading
 * SAM-4040: Lot Category modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 1, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Template;

use LotCategoryTemplate;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\LotCategoryTemplate\LotCategoryTemplateReadRepositoryCreateTrait;

/**
 * Class LotCategoryTemplateLoader
 * @package Sam\Lot\Category\Template
 */
class LotCategoryTemplateLoader extends EntityLoaderBase
{
    use LotCategoryTemplateReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return object LotCategoryTemplate
     *
     * @param int $lotCategoryId
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return LotCategoryTemplate|null
     */
    public function loadTemplate(int $lotCategoryId, int $accountId, bool $isReadOnlyDb = false): ?LotCategoryTemplate
    {
        $lotCategoryTemplate = $this->createLotCategoryTemplateReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterLotCategoryId($lotCategoryId)
            ->loadEntity();
        return $lotCategoryTemplate;
    }

    /**
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return LotCategoryTemplate[]
     */
    public function loadAllTemplate(int $accountId, bool $isReadOnlyDb = false): array
    {
        $lotCategoryTemplates = $this->createLotCategoryTemplateReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->loadEntities();
        return $lotCategoryTemplates;
    }
}
