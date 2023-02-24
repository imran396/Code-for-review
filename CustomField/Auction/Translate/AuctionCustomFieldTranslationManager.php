<?php
/**
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 3, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Translate;

use AuctionCustField;
use Sam\CustomField\Base\Translate\CustomFieldTranslationManager;
use Sam\Core\Constants;

/**
 * Class AuctionCustomFieldTranslationManager
 * @package Sam\CustomField\Auction\Translate\AuctionCustomFieldTranslationManager
 */
class AuctionCustomFieldTranslationManager extends CustomFieldTranslationManager
{
    protected string $translationsFilename = Constants\CustomField::AUCTION_CUSTOM_FIELD_TRANSLATION_FILE;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Refresh translations for auction custom field
     *
     * @param AuctionCustField $auctionCustomField
     * @param string|null $oldName
     */
    public function refresh(AuctionCustField $auctionCustomField, ?string $oldName = null): void
    {
        $this->refreshTranslations($auctionCustomField->Type, $auctionCustomField->Name, $auctionCustomField->Parameters, $oldName);
    }

    /**
     * Delete translations for custom field
     *
     * @param AuctionCustField $auctionCustomField
     */
    public function delete(AuctionCustField $auctionCustomField): void
    {
        $this->deleteTranslations($auctionCustomField->Type, $auctionCustomField->Name);
    }

    /**
     * Return translation of custom field name
     *
     * @param AuctionCustField $auctionCustomField
     * @return string
     */
    public function translateName(AuctionCustField $auctionCustomField): string
    {
        $langKeyName = $this->makeKeyForName($auctionCustomField->Name);
        return $this->getTranslator()->translate($langKeyName, 'auctioncustomfields');
    }

    /**
     * Return translation of custom field parameters
     *
     * @param AuctionCustField $auctionCustomField
     * @return string
     */
    public function translateParameters(AuctionCustField $auctionCustomField): string
    {
        $langKeyParam = $this->makeKeyForParameters($auctionCustomField->Name);
        return $this->getTranslator()->translate($langKeyParam, 'auctioncustomfields');
    }
}
