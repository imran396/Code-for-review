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

namespace Sam\Email\Load;

use AuctionEmailTemplate;
use EmailTemplate;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\AuctionEmailTemplate\AuctionEmailTemplateReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\EmailTemplate\EmailTemplateReadRepositoryCreateTrait;

/**
 * Class EmailTemplateLoader
 * @package Sam\Email\Load
 */
class EmailTemplateLoader extends EntityLoaderBase
{
    use AuctionEmailTemplateReadRepositoryCreateTrait;
    use EmailTemplateReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return EmailTemplate[]
     */
    public function loadAll(int $accountId, bool $isReadOnlyDb = false): array
    {
        $emailTemplates = $this->createEmailTemplateReadRepository()
            ->filterAccountId($accountId)
            ->enableReadOnlyDb($isReadOnlyDb)
            ->loadEntities();
        return $emailTemplates;
    }

    /**
     * @param int $id
     * @param bool $isReadOnlyDb
     * @return EmailTemplate|null
     */
    public function load(int $id, bool $isReadOnlyDb = false): ?EmailTemplate
    {
        $emailTemplate = $this->createEmailTemplateReadRepository()
            ->filterId($id)
            ->enableReadOnlyDb($isReadOnlyDb)
            ->loadEntity();
        return $emailTemplate;
    }

    /**
     * @param string $key
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return EmailTemplate|null
     */
    public function loadByKey(string $key, int $accountId, bool $isReadOnlyDb = false): ?EmailTemplate
    {
        $emailTemplate = $this->createEmailTemplateReadRepository()
            ->filterAccountId($accountId)
            ->filterKey($key)
            ->enableReadOnlyDb($isReadOnlyDb)
            ->loadEntity();
        return $emailTemplate;
    }

    /**
     * @param int|null $auctionId null - absent auction leads to absent result
     * @param string $key
     * @param bool $isReadOnlyDb
     * @return AuctionEmailTemplate|null
     */
    public function loadByKeyAucId(?int $auctionId, string $key, bool $isReadOnlyDb = false): ?AuctionEmailTemplate
    {
        if (!$auctionId || $key === '') {
            return null;
        }

        $auctionEmailTemplate = $this->createAuctionEmailTemplateReadRepository()
            ->filterAuctionId($auctionId)
            ->filterKey($key)
            ->enableReadOnlyDb($isReadOnlyDb)
            ->loadEntity();
        return $auctionEmailTemplate;
    }
}
