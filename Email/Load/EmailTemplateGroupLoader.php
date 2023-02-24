<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Load;

use EmailTemplateGroup;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\EmailTemplateGroup\EmailTemplateGroupReadRepositoryCreateTrait;

/**
 * Class EmailTemplateGroupLoader
 * @package Sam\Email\Load
 */
class EmailTemplateGroupLoader extends CustomizableClass
{
    use EmailTemplateGroupReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load a EmailTemplateGroup
     *
     * @param int|null $id
     * @param bool $isReadOnlyDb
     * @return EmailTemplateGroup|null
     */
    public function load(?int $id, bool $isReadOnlyDb = false): ?EmailTemplateGroup
    {
        if (!$id) {
            return null;
        }

        $emailTemplateGroup = $this->createEmailTemplateGroupReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($id)
            ->loadEntity();
        return $emailTemplateGroup;
    }
}
