<?php
/**
 * Feed content placeholders for lot custom fields are not restricted by lot category and access rights.
 *
 * Placeholders related data for translation and db access
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
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

namespace Sam\Details\Lot\Feed;

use Sam\Core\Data\TypeCast\ArrayCast;

/**
 * Class ConfigManager
 * @package Sam\Details
 */
class ConfigManager extends \Sam\Details\Lot\Base\ConfigManager
{
    /**
     * [] - any role access
     * @var string[]
     */
    protected array $lotCustomFieldAccesses = [];
    /**
     * [] - means any category restriction
     * @var int[]
     */
    protected array $lotCustomFieldCategoryIds = [];

    public static function new(): static
    {
        return self::_new(self::class);
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
            $this->availableLotCustomFieldIds = $this->createLotCustomFieldLoader()->loadAllIds(true);
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
