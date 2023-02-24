<?php
/**
 *
 * SAM-4748: Mailing List Template management classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-06
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\MailingList\Delete;

use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Report\MailingList\Load\MailingListTemplateLoaderAwareTrait;
use Sam\Storage\DeleteRepository\Entity\MailingListTemplateCategories\MailingListTemplateCategoriesDeleteRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\MailingListTemplates\MailingListTemplatesWriteRepositoryAwareTrait;

/**
 * Class MailingListTemplateDeleter
 * @package Sam\Report\MailingList\Delete
 */
class MailingListTemplateDeleter extends CustomizableClass
{
    use EditorUserAwareTrait;
    use MailingListTemplateCategoriesDeleteRepositoryCreateTrait;
    use MailingListTemplateLoaderAwareTrait;
    use MailingListTemplatesWriteRepositoryAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param \MailingListTemplates $mailingListTemplate
     *
     */
    public function delete(\MailingListTemplates $mailingListTemplate): void
    {
        $mailingListTemplate->Deleted = true;
        $this->getMailingListTemplatesWriteRepository()->saveWithModifier($mailingListTemplate, $this->getEditorUserId());
    }

    /**
     * @param int $mailingListTemplateId
     * @param bool $isReadOnlyDb
     */
    public function deleteById(int $mailingListTemplateId, bool $isReadOnlyDb = false): void
    {
        $mailingListTemplate = $this->getMailingListTemplateLoader()->load($mailingListTemplateId, $isReadOnlyDb);
        if (!$mailingListTemplate) {
            log_error("Available mailing list template not found, when deleting" . composeSuffix(['mlt' => $mailingListTemplateId]));
            return;
        }
        $this->delete($mailingListTemplate);
    }

    /**
     * Delete by Mailing list template id
     * @param $mailingListId
     */
    public function deleteCategoriesByMailingListTemplateId($mailingListId): void
    {
        $this->createMailingListTemplateCategoriesDeleteRepository()
            ->filterMailingListId($mailingListId)
            ->delete();
    }
}
