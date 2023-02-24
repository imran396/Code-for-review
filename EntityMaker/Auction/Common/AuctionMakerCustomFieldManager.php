<?php
/**
 * Contains all the logic of working with custom fields
 *
 * SAM-8840: Auction entity-maker module structural adjustments for v3-5
 * SAM-4241: Auction Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 12, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Common;

use AuctionCustData;
use AuctionCustField;
use Sam\Auction\FieldConfig\Provider\AuctionFieldConfigProviderAwareTrait;
use Sam\Auction\FieldConfig\Provider\Map\EntityMakerFieldMap;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\CustomField\Auction\Load\AuctionCustomDataLoaderAwareTrait;
use Sam\CustomField\Auction\Load\AuctionCustomFieldLoaderAwareTrait;
use Sam\EntityMaker\Base\Common\CustomFieldManager;
use Sam\EntityMaker\Base\Dto\ConfigDto;
use Sam\EntityMaker\Base\Dto\InputDto;

/**
 * Class AuctionMakerCustomFieldManager
 * @package Sam\EntityMaker\Auction
 */
class AuctionMakerCustomFieldManager extends CustomFieldManager
{
    use AuctionCustomDataLoaderAwareTrait;
    use AuctionCustomFieldLoaderAwareTrait;
    use AuctionFieldConfigProviderAwareTrait;
    use EntityFactoryCreateTrait;

    /**
     * @var string[]
     */
    protected array $errorMessages = [
        self::CUSTOM_FIELD_DATE_ERROR => 'Invalid date',
        self::CUSTOM_FIELD_DECIMAL_ERROR => 'Should be numeric',
        self::CUSTOM_FIELD_FILE_ERROR => 'Invalid file extension',
        self::CUSTOM_FIELD_HIDDEN_ERROR => 'Hidden',
        self::CUSTOM_FIELD_INTEGER_ERROR => 'Should be numeric integer',
        self::CUSTOM_FIELD_POSTAL_CODE_ERROR => 'Invalid format',
        self::CUSTOM_FIELD_REQUIRED_ERROR => 'Required',
        self::CUSTOM_FIELD_SELECT_INVALID_OPTION_ERROR => 'Has invalid option',
        self::CUSTOM_FIELD_TEXT_UNIQUE_ERROR => 'Value must be unique',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritdoc
     */
    public function construct(
        InputDto $inputDto,
        ConfigDto $configDto
    ): static {
        $this->getAuctionFieldConfigProvider()->setFieldMap(EntityMakerFieldMap::new());
        return parent::construct($inputDto, $configDto);
    }

    public function findCustomFieldByName(string $name): ?AuctionCustField
    {
        foreach ($this->getAllCustomFields() as $customField) {
            if ($this->getCustomFieldsTagName($customField->Name) === $name) {
                return $customField;
            }
        }
        return null;
    }

    /**
     * Load custom fields from auction_cust_field table
     * @return AuctionCustField[]
     */
    protected function loadCustomFields(): array
    {
        $auctionCustomFields = $this->getAuctionCustomFieldLoader()->loadAll();
        return $auctionCustomFields;
    }

    /**
     * Load or create auction custom data
     * @param int $auctionId
     * @param int $customFieldId
     * @return AuctionCustData
     */
    protected function loadCustomDataOrCreate(int $auctionId, int $customFieldId): AuctionCustData
    {
        if ($this->allCustomData === null) {
            $this->allCustomData = $this->getAuctionCustomDataLoader()->loadForAuction($auctionId);
        }
        $auctionCustomData = current(
            array_filter(
                $this->allCustomData,
                static function ($data) use ($customFieldId) {
                    return $data->AuctionCustFieldId === $customFieldId;
                }
            )
        );
        if ($auctionCustomData) {
            return $auctionCustomData;
        }

        $auctionCustomData = $this->createEntityFactory()->auctionCustData();
        $auctionCustomData->AuctionId = $auctionId;
        $auctionCustomData->AuctionCustFieldId = $customFieldId;
        return $auctionCustomData;
    }

    /**
     * @param AuctionCustField $customField
     * @return bool
     */
    protected function isVisible($customField): bool
    {
        $configDto = $this->getConfigDto();
        $isVisible = $this->getAuctionFieldConfigProvider()->isVisibleCustomField(
            $customField->Id,
            $configDto->serviceAccountId
        );
        return $isVisible;
    }

    /**
     * @param AuctionCustField $customField
     * @return bool
     */
    protected function isRequired($customField): bool
    {
        $configDto = $this->getConfigDto();
        $isRequired = $this->getAuctionFieldConfigProvider()->isRequiredCustomField(
            $customField->Id,
            $configDto->serviceAccountId
        );
        return $isRequired;
    }
}
