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

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Meta\Descriptor\Descriptor;

/**
 * Class DescriptorAdditionalConstraintsBuilder
 * @package Sam\Installation\Config
 */
class DescriptorAdditionalConstraintsBuilder extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Add additional constraints for different data types.
     * @param Descriptor $descriptor
     * @return Descriptor
     */
    public function addAdditionalConstraints(Descriptor $descriptor): Descriptor
    {
        $descriptor = $this->addConstraintsForArrays($descriptor);
        $descriptor = $this->addConstraintsForKnownSets($descriptor);
        return $descriptor;
    }

    /**
     * Add additional constraints for array
     * @param Descriptor $descriptor
     * @return Descriptor
     */
    protected function addConstraintsForArrays(Descriptor $descriptor): Descriptor
    {
        if (
            $descriptor->getType() === Constants\Type::T_ARRAY
            && $descriptor->getEditComponent() !== Constants\Installation::ECOM_MULTISELECT
        ) {
            $constraints = $descriptor->getConstraints();
            $merged = DescriptorConstraintsBuilderHelper::new()->mergeConstraints(
                $constraints,
                Constants\Installation::ADDITIONAL_CONSTRAINTS_FOR_ARRAY_TYPE
            );
            $descriptor->setConstraints($merged);
        }
        return $descriptor;
    }

    /**
     * Add additional constraints for Known sets.
     * @param Descriptor $descriptor
     * @return Descriptor
     */
    protected function addConstraintsForKnownSets(Descriptor $descriptor): Descriptor
    {
        $knownSet = $descriptor->getKnownSet();
        $knownSetNames = $descriptor->getKnownSetNames();
        if (!empty($knownSet) || !empty($knownSetNames)) {
            if (!empty($knownSetNames)) {
                $knownSet = array_keys($knownSetNames);
            }
            $constraints = $this->mergeConstraintsForKnownSets($descriptor, $knownSet);
            $descriptor->setConstraints($constraints);
        }
        return $descriptor;
    }

    /**
     * @param Descriptor $descriptor
     * @param array $knownSet
     * @return array
     */
    protected function mergeConstraintsForKnownSets(Descriptor $descriptor, array $knownSet): array
    {
        $constraints = $descriptor->getConstraints();
        $merged = DescriptorConstraintsBuilderHelper::new()->mergeConstraints(
            $constraints,
            [Constants\Installation::C_KNOWN_SET => $knownSet]
        );
        return $merged;
    }
}
