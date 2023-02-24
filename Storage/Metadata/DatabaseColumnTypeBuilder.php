<?php
/**
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Metadata;

use Sam\Core\Service\CustomizableClass;

/**
 * Convert database column type representation to DatabaseColumnType structure
 *
 * Class ColumnTypeBuilder
 * @package Sam\Storage\Metadata
 */
class DatabaseColumnTypeBuilder extends CustomizableClass
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
     * @param string $dbColumnTypeRepresentation
     * @return DatabaseColumnType
     */
    public function buildFromDbColumnTypeRepresentation(string $dbColumnTypeRepresentation): DatabaseColumnType
    {
        $params = $this->detectTypeParameters($dbColumnTypeRepresentation);
        if ($params['name'] === 'enum') {
            return $this->buildEnumType($params['options']);
        }
        return $this->buildType($params['name'], $params['options'], $params['extra']);
    }

    /**
     * @param string $dbColumnTypeRepresentation
     * @return array
     */
    private function detectTypeParameters(string $dbColumnTypeRepresentation): array
    {
        $matches = [];
        preg_match('/^([a-z]*)(\(.*\))?\s?(.*)$/', $dbColumnTypeRepresentation, $matches);
        [, $name, $options, $extra] = $matches;
        if ($options) {
            $options = trim($options, '()');
        }
        return ['name' => $name, 'options' => $options, 'extra' => $extra];
    }

    /**
     * @param string $name
     * @param string $length
     * @param string $extra
     * @return DatabaseColumnType
     */
    private function buildType(string $name, string $length, string $extra): DatabaseColumnType
    {
        $type = new DatabaseColumnType($name);
        if ($length) {
            $type->setLength($length);
        }
        if ($extra === 'unsigned') {
            $type->setUnsigned(true);
        }
        return $type;
    }

    /**
     * @param string $enumChoicesDbRepresentation
     * @return DatabaseColumnType
     */
    private function buildEnumType(string $enumChoicesDbRepresentation): DatabaseColumnType
    {
        $enumChoicesDbRepresentation = trim($enumChoicesDbRepresentation, '()');
        $enumChoices = explode(',', $enumChoicesDbRepresentation);
        $enumChoices = array_map(
            static function (string $enumChoice) {
                return trim($enumChoice, '\' ');
            },
            $enumChoices
        );

        $type = new DatabaseColumnType('enum');
        $type->setEnumChoices($enumChoices);
        return $type;
    }
}
