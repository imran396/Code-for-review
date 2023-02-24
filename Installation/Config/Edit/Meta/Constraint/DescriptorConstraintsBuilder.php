<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           09-15, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Meta\Constraint;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Validation constraints builder class for Descriptor class.
 * Class DescriptorConstraintsBuilder
 * @package Sam\Installation\Config
 */
class DescriptorConstraintsBuilder extends CustomizableClass
{
    /**
     * Validation constraints array with validation rules.
     * @var array
     */
    protected array $constraints = [];

    /**
     * available data types
     * @var string[]
     */
    protected const AVAILABLE_TYPES = [
        Constants\Type::T_ARRAY,
        Constants\Type::T_BOOL,
        Constants\Type::T_FLOAT,
        Constants\Type::T_INTEGER,
        Constants\Type::T_NULL,
        Constants\Type::T_STRING,
    ];

    /**
     * Validation constraints assigned for different data types.
     * @var string[]
     */
    protected const CONSTRAINTS_FOR_TYPES = [
        Constants\Type::T_ARRAY => Constants\Installation::C_IS_ARRAY,
        Constants\Type::T_INTEGER => Constants\Installation::C_INT,
        Constants\Type::T_BOOL => Constants\Installation::C_BOOL,
        Constants\Type::T_FLOAT => Constants\Installation::C_FLOAT,
        Constants\Type::T_STRING => Constants\Installation::C_STRING,
        Constants\Type::T_NULL => Constants\Installation::C_STRING,
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
     * @return array
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }

    /**
     * @param array $constraints
     * @return static
     */
    public function setConstraints(array $constraints): static
    {
        $this->constraints = $constraints;
        return $this;
    }

    /**
     * Add constraints
     * @param array|null $constraints
     * @return static
     */
    public function addConstraints(?array $constraints): static
    {
        $constraints = $this->normalizeConstraints($constraints);
        $this->setConstraints($constraints);
        return $this;
    }

    /**
     * Add constraints for different data types.
     * @param string $type
     * @return static
     */
    public function addConstraintsForDataType(string $type): static
    {
        $constraint = $this->findConstraintForDataType($type);
        if (!empty($constraint)) {
            $this->addConstraints([$constraint]);
        }
        return $this;
    }

    /**
     * @param array $constraints
     * @return array
     */
    public function order(array $constraints): array
    {
        $ordered = [];
        foreach (Constants\Installation::CONSTRAINTS_BY_CHECKING_PRIORITY as $constraint) {
            if (array_key_exists($constraint, $constraints)) {
                $ordered[$constraint] = $constraints[$constraint];
            }
        }
        return $ordered;
    }

    /**
     * @param array|null $constraints
     * @return array
     */
    protected function normalizeConstraints(?array $constraints): array
    {
        $normalizedConstraints = [];
        foreach ($constraints ?: [] as $key => $value) {
            if (in_array($key, Constants\Installation::WITH_ARGUMENT_CONSTRAINTS, true)) {
                $normalizedConstraints[$key] = $value;
            } else {
                $normalizedConstraints[$value] = null;
            }
        }
        $this->constraints = !empty($this->constraints)
            ? array_merge($this->constraints, $normalizedConstraints)
            : $normalizedConstraints;
        $normalizedConstraints = $this->order($this->constraints);
        return $normalizedConstraints;
    }

    /**
     * @param string $type
     * @return string
     */
    protected function findConstraintForDataType(string $type): string
    {
        $constraint = '';
        if (
            array_key_exists($type, self::CONSTRAINTS_FOR_TYPES)
            && in_array($type, self::AVAILABLE_TYPES, true)
        ) {
            $constraint = self::CONSTRAINTS_FOR_TYPES[$type];
        }
        return $constraint;
    }
}
