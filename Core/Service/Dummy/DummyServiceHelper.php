<?php
/**
 * Helper methods for implementation of dummy services.
 *
 * SAM-8543: Dummy classes for service stubbing in unit tests
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Service\Dummy;

use QBaseClass;
use Sam\Core\Db\Schema\DbSchemaConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;

/**
 * Class DummyServiceHelper
 * @package Sam\Core\Service\Dummy
 */
class DummyServiceHelper extends CustomizableClass
{
    use OptionalsTrait;

    // --- Incoming values ---

    public const OP_GLUE_STRING = 'glueString';
    public const OP_GLUE_PK = 'gluePk';

    // --- Internal values ---

    private const GLUE_STRING_DEF = '/';
    private const GLUE_PK_DEF = '_';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Concatenate arguments with "/".
     * Entity is converted to values of its primary keys concatenated by "_"
     * @param array $arguments
     * @return string
     */
    public function toString(array $arguments): string
    {
        foreach ($arguments as $index => $argument) {
            if ($this->isEntity($argument)) {
                $arguments[$index] = $this->entityToString($argument);
            } elseif (is_array($argument)) {
                $arguments[$index] = $this->arrayToString($argument);
            }
        }
        $glue = (string)$this->fetchOptional(self::OP_GLUE_STRING);
        return implode($glue, $arguments);
    }

    /**
     * Check if argument is an entity.
     * @param $argument
     * @return bool
     */
    protected function isEntity($argument): bool
    {
        return $argument instanceof QBaseClass
            && array_key_exists(get_class($argument), DbSchemaConstants::PK_TABLE_PROPERTY_MAP);
    }

    /**
     * Represent entity as string by gluing its primary keys.
     * @param QBaseClass $entity
     * @return string
     */
    protected function entityToString(QBaseClass $entity): string
    {
        $map = DbSchemaConstants::PK_TABLE_PROPERTY_MAP;
        $class = get_class($entity);
        $pkValues = [];
        foreach ($map[$class]['pk_properties'] as $property) {
            $pkValues[] = $entity->$property ?? null;
        }
        $glue = (string)$this->fetchOptional(self::OP_GLUE_PK);
        return implode($glue, $pkValues);
    }

    protected function arrayToString(array $array): string
    {
        return implode('/', array_values($array));
    }

    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_GLUE_STRING] = $optionals[self::OP_GLUE_STRING] ?? self::GLUE_STRING_DEF;
        $optionals[self::OP_GLUE_PK] = $optionals[self::OP_GLUE_PK] ?? self::GLUE_PK_DEF;
        $this->setOptionals($optionals);
    }
}
