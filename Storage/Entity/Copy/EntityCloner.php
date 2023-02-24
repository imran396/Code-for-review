<?php
/**
 * SAM-6611: Move entity cloning logic to customizable class
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 08, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\Copy;

use QBaseClass;
use Sam\Core\Service\CustomizableClass;

/**
 * Class EntityCloner
 * @package Sam\Storage\Entity
 */
class EntityCloner extends CustomizableClass
{
    /**
     * ID and Meta-data fields should be dropped
     */
    protected array $adjustments = [
        'Id' => null,
        '__Restored' => false,
        'CreatedOn' => null,
        'ModifiedOn' => null,
        'CreatedBy' => null,
        'ModifiedBy' => null,
        'RowVersion' => null,
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Clone model object.
     * ID and Meta-data fields should be dropped.
     * Cannot use clone keyword operation, because we want to track __Modified fields.
     * @param QBaseClass $sourceEntity Model object
     * @return QBaseClass
     */
    public function cloneRecord(QBaseClass $sourceEntity): QBaseClass
    {
        $targetEntity = $sourceEntity->clone();
        foreach ($this->adjustments as $property => $value) {
            $targetEntity->$property = $value;
            $targetEntity->unsetModifiedField($property);
        }
        return $targetEntity;
    }
}
