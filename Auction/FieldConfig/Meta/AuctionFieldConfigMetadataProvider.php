<?php
/**
 * SAM-9688: Ability to make Auction and Lot/Item fields required to have values: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\FieldConfig\Meta;

use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Load\AuctionCustomFieldLoaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class AuctionFieldConfigMetadataProvider
 * @package Sam\Auction\FieldConfig\Meta
 */
class AuctionFieldConfigMetadataProvider extends CustomizableClass
{
    use AuctionCustomFieldLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;

    /**
     * @var AuctionFieldConfigMetadata[]
     */
    protected ?array $metadataCache = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get metadata from a config file and return for a field by index
     *
     * @param string $index
     * @return AuctionFieldConfigMetadata
     */
    public function getByIndex(string $index): AuctionFieldConfigMetadata
    {
        $fieldsConfigMetadata = $this->getAll();
        if (!isset($fieldsConfigMetadata[$index])) {
            throw new InvalidArgumentException("Metadata for index '{$index}' not found");
        }
        return $fieldsConfigMetadata[$index];
    }

    /**
     * Get all fields metadata from a config file and build metadata dtos
     *
     * @return AuctionFieldConfigMetadata[]
     */
    public function getAll(): array
    {
        if ($this->metadataCache === null) {
            $this->metadataCache = $this->buildMetadata();
        }
        return $this->metadataCache;
    }

    /**
     * @return AuctionFieldConfigMetadata[]
     */
    protected function buildMetadata(): array
    {
        $fieldsConfigMetadataDefinition = $this->cfg()->get('fieldConfig->auction')->toArray();
        $fieldsConfigMetadata = [];
        $indexPrev = '';
        foreach ($fieldsConfigMetadataDefinition as $index => $metadataDefinition) {
            if ($index === Constants\AuctionFieldConfig::CUSTOM_FIELDS) {
                $customFields = $this->getAuctionCustomFieldLoader()->loadAll(true);
                foreach ($customFields as $customField) {
                    $customFieldIndex = 'fc' . $customField->Id;
                    $fieldsConfigMetadata[$customFieldIndex] = AuctionFieldConfigMetadata::new()->construct(
                        $indexPrev,
                        true,
                        $customField->Required
                    );
                    $indexPrev = $customFieldIndex;
                }
            } else {
                $fieldsConfigMetadata[$index] = AuctionFieldConfigMetadata::new()->construct(
                    $indexPrev,
                    $metadataDefinition['requirable'] ?? false,
                    $metadataDefinition['alwaysRequired'] ?? false,
                );
                $indexPrev = $index;
            }
        }
        return $fieldsConfigMetadata;
    }
}
