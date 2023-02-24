<?php
/**
 *
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 09, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Entity\Internal\Build;

use Sam\Core\Service\CustomizableClass;
use Sam\Log\Entity\Config\EntityDumpConfig;

/**
 * Class OutputBuilder
 * @package Sam\Log\Entity
 * @internal
 */
class OutputBuilder extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        return $this;
    }

    /**
     * @param $entity
     * @param EntityDumpConfig $entityConfig
     * @param array $idInfo
     * @return string
     */
    public function build($entity, EntityDumpConfig $entityConfig, array $idInfo = []): string
    {
        $rows = [
            'Modified fields for ' . get_class($entity) . composeSuffix($idInfo) . ':'
        ];
        $propertyModificationRenderer = PropertyModificationRenderer::new();
        foreach ($entity->__Modified as $property => $oldValue) {
            $propertyConfig = $entityConfig->getPropertyConfig($property);
            $row = $propertyModificationRenderer->render($oldValue, $entity->$property, $propertyConfig);
            if ($row) {
                $rows[] = $row;
            }
        }
        $output = implode("\n", $rows);
        return $output;
    }
}
