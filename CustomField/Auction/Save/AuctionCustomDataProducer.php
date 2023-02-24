<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/15/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Save;

use AuctionCustData;
use AuctionCustField;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Translate\AuctionCustomFieldTranslationManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionCustData\AuctionCustDataWriteRepositoryAwareTrait;

/**
 * Class AuctionCustomDataBuilder
 * @package
 */
class AuctionCustomDataProducer extends CustomizableClass
{
    use AuctionCustDataWriteRepositoryAwareTrait;
    use AuctionCustomFieldTranslationManagerAwareTrait;
    use EntityFactoryCreateTrait;

    protected bool $enabledAutoSave = true;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $enableAutoSave
     * @return $this
     */
    public function construct(bool $enableAutoSave = true): static
    {
        $this->enabledAutoSave = $enableAutoSave;
        return $this;
    }

    /**
     * Create a new instance, initialized with passed auction, custom field ids and default values
     *
     * @param AuctionCustField $auctionCustomField
     * @param int|null $auctionId null for new data object
     * @param int $editorUserId
     * @param bool $isTranslating
     * @return AuctionCustData
     */
    public function produce(
        AuctionCustField $auctionCustomField,
        ?int $auctionId,
        int $editorUserId,
        bool $isTranslating = false
    ): AuctionCustData {
        $auctionCustomData = new AuctionCustData();
        $auctionCustomData->AuctionId = $auctionId;
        $auctionCustomData->AuctionCustFieldId = $auctionCustomField->Id;
        $auctionCustomData->Active = true;
        $parameters = $isTranslating
            ? $this->getAuctionCustomFieldTranslationManager()->translateParameters($auctionCustomField)
            : $auctionCustomField->Parameters;
        if (in_array(
            $auctionCustomField->Type,
            [Constants\CustomField::TYPE_INTEGER, Constants\CustomField::TYPE_CHECKBOX],
            true
        )
        ) {
            if (ctype_digit($parameters)) {
                $auctionCustomData->Numeric = (int)$parameters;
            }
        } elseif (in_array(
            $auctionCustomField->Type,
            [
                Constants\CustomField::TYPE_TEXT,
                Constants\CustomField::TYPE_FULLTEXT,
                Constants\CustomField::TYPE_RICHTEXT,
                Constants\CustomField::TYPE_PASSWORD,
            ],
            true
        )
        ) {
            $auctionCustomData->Text = $parameters;
        }

        if ($this->enabledAutoSave) {
            $this->getAuctionCustDataWriteRepository()->saveWithModifier($auctionCustomData, $editorUserId);
        }

        return $auctionCustomData;
    }
}
