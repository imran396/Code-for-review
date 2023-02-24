<?php
/**
 *
 * SAM-4748: Mailing List Template management classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-05
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\MailingList\Load;

use MailingListTemplateCategories;
use MailingListTemplates;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\MailingListTemplateCategories\MailingListTemplateCategoriesReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\MailingListTemplates\MailingListTemplatesReadRepositoryCreateTrait;

/**
 * Class MailingListTemplateLoader
 * @package Sam\Report\MailingList\Load
 */
class MailingListTemplateLoader extends EntityLoaderBase
{
    use EntityMemoryCacheManagerAwareTrait;
    use MailingListTemplateCategoriesReadRepositoryCreateTrait;
    use MailingListTemplatesReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $mailingListId null leads to result null
     * @param bool $isReadOnlyDb
     * @return MailingListTemplates|null
     */
    public function load(?int $mailingListId, bool $isReadOnlyDb = false): ?MailingListTemplates
    {
        if (!$mailingListId) {
            return null;
        }

        $mailingListTemplates = $this->createMailingListTemplatesReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($mailingListId)
            ->loadEntity();
        return $mailingListTemplates;
    }

    /**
     * @param int|null $mailingListId null leads to null result
     * @param int|null $lotCategoryId null leads to null result
     * @param bool $isReadOnlyDb
     * @return MailingListTemplateCategories|null
     */
    public function loadTemplateCategory(?int $mailingListId, ?int $lotCategoryId, bool $isReadOnlyDb = false): ?MailingListTemplateCategories
    {
        if (!$mailingListId || !$lotCategoryId) {
            return null;
        }

        $mailingListTemplateCategories = $this->createMailingListTemplateCategoriesReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterMailingListId($mailingListId)
            ->filterCategoryId($lotCategoryId)
            ->loadEntity();
        return $mailingListTemplateCategories;
    }

    /**
     * @param int|null $mailingListId null leads to empty array result
     * @param bool $isReadOnlyDb
     * @return MailingListTemplateCategories[]
     */
    public function loadTemplateCategories(?int $mailingListId, bool $isReadOnlyDb = false): array
    {
        if (!$mailingListId) {
            return [];
        }

        $mailingListTemplateCategories = $this->createMailingListTemplateCategoriesReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterMailingListId($mailingListId)
            ->loadEntities();
        return $mailingListTemplateCategories;
    }

    /**
     * @param int|null $mailingListId null leads to empty array result
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadTemplateCategoryIds(?int $mailingListId, bool $isReadOnlyDb = false): array
    {
        if (!$mailingListId) {
            return [];
        }

        $rows = $this->createMailingListTemplateCategoriesReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterMailingListId($mailingListId)
            ->select(['mltc.category_id'])
            ->loadRows();
        $mailingListTemplateCategoryIds = ArrayCast::arrayColumnInt($rows, 'category_id');
        return $mailingListTemplateCategoryIds;
    }
}
