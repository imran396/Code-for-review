<?php
/** @noinspection PhpUnused */

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

use LotItemCustField;
use Sam\CustomField\Lot\Load\LotCustomFieldLoader;

/**
 * Trait LotCustomFieldsAwareTrait
 * @package Sam\Storage\Entity\AwareTrait
 */
trait LotCustomFieldsAwareTrait
{
    /** @var LotItemCustField[] */
    private ?array $lotCustomFields = null;

    /**
     * @return int[]
     */
    public function getLotCustomFieldIds(): array
    {
        $lotCustomFieldIds = [];
        foreach ($this->getLotCustomFields() as $lotCustomField) {
            $lotCustomFieldIds[] = $lotCustomField->Id;
        }
        return $lotCustomFieldIds;
    }

    /**
     * Return array of lot custom fields. By default it loads all active.
     * @return LotItemCustField[]
     */
    public function getLotCustomFields(): array
    {
        if ($this->lotCustomFields === null) {
            $this->lotCustomFields = LotCustomFieldLoader::new()->loadAll();
        }
        return $this->lotCustomFields;
    }

    /**
     * @param LotItemCustField[] $lotCustomFields
     * @return static
     */
    public function setLotCustomFields(array $lotCustomFields): static
    {
        $this->lotCustomFields = $lotCustomFields;
        return $this;
    }

    /**
     * @param int|null $id
     * @param bool $isReadOnlyDb
     * @return LotItemCustField|null
     */
    public function getLotCustomFieldById(?int $id, bool $isReadOnlyDb = false): ?LotItemCustField
    {
        $lotCustomField = null;
        if ($this->lotCustomFields === null) {
            $lotCustomField = LotCustomFieldLoader::new()->load($id, $isReadOnlyDb);
        } else {
            foreach ($this->getLotCustomFields() as $checkedCustomField) {
                if ($checkedCustomField->Id === $id) {
                    $lotCustomField = $checkedCustomField;
                    break;
                }
            }
            if (!$lotCustomField) {
                $lotCustomField = LotCustomFieldLoader::new()->load($id, $isReadOnlyDb);
            }
        }
        if ($lotCustomField) {
            return $lotCustomField;
        }

        log_error("Lot custom field not found" . composeSuffix(['licf' => $id]));
        return null;
    }
}
