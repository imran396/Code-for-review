<?php
/**
 * Helper class for custom field auction fields
 * SAM-4039: Auction deleter class
 * SAM-6671: Auction deleter for v3.5
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @since           May 13, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * @property string Encoding
 */

namespace Sam\CustomField\Auction\Delete;

use AuctionCustField;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Translate\AuctionCustomFieldTranslationManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionCustField\AuctionCustFieldReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionCustField\AuctionCustFieldWriteRepositoryAwareTrait;

/**
 * Class FieldDeleter
 * @package Sam\CustomField\Auction\Delete
 */
class AuctionCustomFieldDeleter extends CustomizableClass
{
    use AuctionCustFieldReadRepositoryCreateTrait;
    use AuctionCustFieldWriteRepositoryAwareTrait;
    use AuctionCustomDataDeleterCreateTrait;
    use AuctionCustomFieldTranslationManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Perform deleting actions for custom field and related data
     *
     * @param int $auctionCustomFieldId
     * @param int $editorUserId
     * @return void
     */
    public function deleteById(int $auctionCustomFieldId, int $editorUserId): void
    {
        $auctionCustomField = $this->createAuctionCustFieldReadRepository()
            ->filterId($auctionCustomFieldId)
            ->loadEntity();
        if ($auctionCustomField) {
            $this->delete($auctionCustomField, $editorUserId);
        }
    }

    /**
     * Perform deleting actions for custom field and related data
     *
     * @param AuctionCustField $auctionCustomField
     * @param int $editorUserId
     * @return void
     */
    public function delete(AuctionCustField $auctionCustomField, int $editorUserId): void
    {
        $auctionCustomField->Active = false;
        $this->getAuctionCustFieldWriteRepository()->saveWithModifier($auctionCustomField, $editorUserId);
        $this->createAuctionCustomDataDeleter()->deleteForFieldId($auctionCustomField->Id, $editorUserId);
        $this->getAuctionCustomFieldTranslationManager()->delete($auctionCustomField);
    }
}
