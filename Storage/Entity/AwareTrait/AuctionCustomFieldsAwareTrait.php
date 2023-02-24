<?php

/**
 * SAM-4819: Entity aware traits
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/17/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\AwareTrait;

use AuctionCustField;
use Sam\CustomField\Auction\Load\AuctionCustomFieldLoader;

/**
 * Trait AuctionCustomFieldsAwareTrait
 * @package Sam\Storage\Entity\AwareTrait
 */
trait AuctionCustomFieldsAwareTrait
{
    /** @var AuctionCustField[]|null */
    private ?array $auctionCustomFields = null;

    /**
     * @return int[]
     */
    public function getAuctionCustomFieldIds(): array
    {
        $auctionCustomFieldIds = [];
        foreach ($this->getAuctionCustomFields() as $auctionCustomField) {
            $auctionCustomFieldIds[] = $auctionCustomField->Id;
        }
        return $auctionCustomFieldIds;
    }

    /**
     * Load array of auction custom fields
     * @return AuctionCustField[]
     */
    public function getAuctionCustomFields(): array
    {
        if ($this->auctionCustomFields === null) {
            $this->auctionCustomFields = AuctionCustomFieldLoader::new()->loadAll();
        }
        return $this->auctionCustomFields;
    }

    /**
     * @param AuctionCustField[] $auctionCustomFields
     * @return static
     */
    public function setAuctionCustomFields(array $auctionCustomFields): static
    {
        $this->auctionCustomFields = $auctionCustomFields;
        return $this;
    }

    /**
     * @param int $auctionCustomFieldId
     * @param bool $isReadOnlyDb
     * @return AuctionCustField|null
     */
    public function getAuctionCustomFieldById(int $auctionCustomFieldId, bool $isReadOnlyDb = false): ?AuctionCustField
    {
        $auctionCustomField = null;
        if ($this->auctionCustomFields === null) {
            $auctionCustomField = AuctionCustomFieldLoader::new()->loadById($auctionCustomFieldId, $isReadOnlyDb);
        } else {
            foreach ($this->getAuctionCustomFields() as $checkedCustomField) {
                if ($checkedCustomField->Id === $auctionCustomFieldId) {
                    $auctionCustomField = $checkedCustomField;
                    break;
                }
            }
            if (!$auctionCustomField) {
                $auctionCustomField = AuctionCustomFieldLoader::new()->loadById($auctionCustomFieldId, $isReadOnlyDb);
            }
        }
        if ($auctionCustomField) {
            return $auctionCustomField;
        }

        error_log("Auction custom field not found" . composeSuffix(['acf' => $auctionCustomFieldId]));
        return null;
    }
}
