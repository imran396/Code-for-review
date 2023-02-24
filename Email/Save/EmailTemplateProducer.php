<?php
/**
 * SAM-4502 : Email template modules
 * https://bidpath.atlassian.net/browse/SAM-4502
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/17/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Save;

use EmailTemplate;
use Sam\Core\Service\CustomizableClass;
use Sam\Email\Load\EmailTemplateLoaderAwareTrait;
use Sam\Storage\Entity\Copy\EntityClonerCreateTrait;
use Sam\Storage\WriteRepository\Entity\EmailTemplate\EmailTemplateWriteRepositoryAwareTrait;

/**
 * Class EmailTemplateProducer
 * @package Sam\Email\Save
 */
class EmailTemplateProducer extends CustomizableClass
{
    use EmailTemplateLoaderAwareTrait;
    use EmailTemplateWriteRepositoryAwareTrait;
    use EntityClonerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Copy emailTemplates from provided account
     * @param int $sourceAccountId
     * @param int $targetAccountId
     * @param int $editorUserId
     */
    public function cloneFromAccount(int $sourceAccountId, int $targetAccountId, int $editorUserId): void
    {
        foreach ($this->getEmailTemplateLoader()->loadAll($sourceAccountId) as $originalEmailTemplate) {
            /** @var EmailTemplate $emailTemplate */
            $emailTemplate = $this->createEntityCloner()->cloneRecord($originalEmailTemplate);
            $emailTemplate->AccountId = $targetAccountId;
            $this->getEmailTemplateWriteRepository()->saveWithModifier($emailTemplate, $editorUserId);
        }
    }
}
