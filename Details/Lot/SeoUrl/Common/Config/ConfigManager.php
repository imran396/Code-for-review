<?php
/**
 * Placeholders related data for translation and db access
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 * SAM-6595: Templated-content building - simplify module structure for v3.5
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Jul 2, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\SeoUrl\Common\Config;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;

/**
 * Class ConfigManager
 * @package Sam\Details
 */
class ConfigManager extends \Sam\Details\Lot\Base\ConfigManager
{
    /**
     * @var string[]
     */
    protected ?array $availableKeys = [
        Constants\LotDetail::PL_ACCOUNT_COMPANY,
        Constants\LotDetail::PL_ACCOUNT_ID,
        Constants\LotDetail::PL_ACCOUNT_NAME,
        Constants\LotDetail::PL_AUCTION_EVENT_ID,
        Constants\LotDetail::PL_AUCTION_ID,
        Constants\LotDetail::PL_AUCTION_NAME,
        Constants\LotDetail::PL_AUCTION_TYPE,
        Constants\LotDetail::PL_AUCTION_TYPE_LANG,
        Constants\LotDetail::PL_BUY_NOW_PRICE,
        Constants\LotDetail::PL_DESCRIPTION,
        Constants\LotDetail::PL_FEATURED,
        Constants\LotDetail::PL_ITEM_NO,
        Constants\LotDetail::PL_LOT_NO,
        Constants\LotDetail::PL_NAME,
        Constants\LotDetail::PL_QUANTITY,
        Constants\LotDetail::PL_SALE_NO,
        Constants\LotDetail::PL_SEO_URL,
    ];

    /**
     * Restrict access by Visitor role
     * @var string[]
     */
    protected array $lotCustomFieldAccesses = [Constants\Role::VISITOR];
    /**
     * [] - means any category restriction
     * @var int[]
     */
    protected array $lotCustomFieldCategoryIds = [];

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function initInstance(): static
    {
        $this->enabledTypes[Constants\Placeholder::LANG_LABEL] = false;
        $this->enabledTypes[Constants\Placeholder::BEGIN_END] = false;
        return $this;
    }

    /**
     * @return static
     */
    public function construct(): static
    {
        $this->enableLotCustomFields(true);
        return parent::construct();
    }

    /**
     * Available custom field list depends on their access permissions and linked categories
     * @return int[]
     */
    public function getAvailableLotCustomFieldIds(): array
    {
        if ($this->availableLotCustomFieldIds === null) {
            $lotCategoryIds = (array)$this->getLotCustomFieldCategoryIds();
            $accesses = $this->getLotCustomFieldAccesses();
            $this->availableLotCustomFieldIds = $this->createLotCustomFieldLoader()
                ->loadCustomFieldIdsByCategoryIds($lotCategoryIds, $accesses);
        }
        return $this->availableLotCustomFieldIds;
    }

    /**
     * @return string[]
     */
    public function getLotCustomFieldAccesses(): array
    {
        return $this->lotCustomFieldAccesses;
    }

    /**
     * @param string[] $accesses
     */
    public function setLotCustomFieldAccesses(array $accesses): static
    {
        $this->lotCustomFieldAccesses = ArrayCast::makeStringArray($accesses);
        return $this;
    }

    /**
     * @return int[]|null
     */
    public function getLotCustomFieldCategoryIds(): ?array
    {
        return $this->lotCustomFieldCategoryIds;
    }

    /**
     * Define custom field restriction by category ids
     * @param int|int[]|null $ids
     */
    public function setLotCustomFieldCategoryIds(int|array|null $ids): static
    {
        $this->lotCustomFieldCategoryIds = ArrayCast::makeIntArray($ids);
        return $this;
    }
}
