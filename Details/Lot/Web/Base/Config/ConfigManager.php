<?php
/**
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

namespace Sam\Details\Lot\Web\Base\Config;

use Sam\Core\Data\TypeCast\ArrayCast;

/**
 * Class ConfigManager
 * @package Sam\Details
 */
abstract class ConfigManager extends \Sam\Details\Lot\Base\ConfigManager
{
    /**
     * [] - means any access (don't filter by access)
     * @var string[]
     */
    protected array $lotCustomFieldAccesses = [];
    /**
     * [] - means any category restriction
     * @var int[]
     */
    protected ?array $lotCustomFieldCategoryIds = null;

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
     * @param int[] $ids
     */
    public function setLotCustomFieldCategoryIds(array $ids): static
    {
        $this->lotCustomFieldCategoryIds = $ids;
        return $this;
    }
}
