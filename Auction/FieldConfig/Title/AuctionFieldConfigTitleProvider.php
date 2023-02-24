<?php
/**
 * SAM-9688: Ability to make Auction and Lot/Item fields required to have values: Implementation (Developer)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 09, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\FieldConfig\Title;

use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Load\AuctionCustomFieldLoaderAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class AuctionFieldConfigTitleProvider
 * @package Sam\Auction\FieldConfig\Title
 */
class AuctionFieldConfigTitleProvider extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use AuctionCustomFieldLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Provides title for field config by index
     *
     * @param string $fieldConfigKey
     * @return string
     */
    public function get(string $fieldConfigKey): string
    {
        if (str_starts_with($fieldConfigKey, 'fc')) {
            $customFieldId = str_replace('fc', '', $fieldConfigKey);
            $customField = $this->getAuctionCustomFieldLoader()->loadById((int)$customFieldId);
            return $customField->Name ?? 'N/A';
        }

        return $this->getAdminTranslator()->trans(sprintf('auctions.field_config.%s.title', $fieldConfigKey), [], 'admin');
    }
}
