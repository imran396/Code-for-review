<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           10-19, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Meta\Constraint;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Meta\Descriptor\Descriptor;

/**
 * Class DescriptorConstraintsBuilderHelper
 * @package Sam\Installation\Config
 */
class DescriptorConstraintsBuilderHelper extends CustomizableClass
{
    use DescriptorConstraintsBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $constraints1
     * @param array $constraints2
     * @return array
     */
    public function mergeConstraints(array $constraints1, array $constraints2): array
    {
        $constraints = $this->getDescriptorConstraintsBuilder()
            ->setConstraints($constraints1)
            ->addConstraints($constraints2)
            ->getConstraints();
        return $constraints;
    }

    /**
     * @param Descriptor $descriptor
     * @return Descriptor
     */
    public function addConstraintsForDataType(Descriptor $descriptor): Descriptor
    {
        $constraints = $this->collectNativeAndTypeConstraints($descriptor);
        $descriptor->setConstraints($constraints);
        return $descriptor;
    }

    /**
     * Collect in array descriptor's native constraints from *.meta.php and constraints required by descriptor data type
     * @param Descriptor $descriptor
     * @return array
     */
    protected function collectNativeAndTypeConstraints(Descriptor $descriptor): array
    {
        $nativeConstraints = $descriptor->getConstraints();
        $constraints = $this->getDescriptorConstraintsBuilder()
            ->setConstraints($nativeConstraints)
            ->addConstraintsForDataType($descriptor->getType())
            ->getConstraints();
        return $constraints;
    }
}
