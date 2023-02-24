<?php
/**
 * Help methods for auction custom field data loading
 *
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 18, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Base\Help;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\Core\Transform\Text\TextTransformer;

/**
 * Class BaseCustomFieldHelper
 * @package Sam\CustomField\Base\Help
 */
class BaseCustomFieldHelper extends CustomizableClass
{
    protected array $types = [];    // to define available types
    protected string $customMethodPrefix = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array of auction custom field types: key: type id, value: type name
     * #[Pure]
     */
    public function getTypeNames(): array
    {
        return array_intersect_key(Constants\CustomField::$typeNames, array_flip($this->types));
    }

    /**
     * @param int $type
     * @return string
     * #[Pure]
     */
    public function makeTypeName(int $type): string
    {
        return Constants\CustomField::$typeNames[$type] ?? '';
    }

    /**
     * Return name of custom method
     *
     * @param string $name Custom field name
     * @param string $type Method type: 'Create', 'Init', 'Render', 'Validate', 'Save'
     * @return string
     * #[Pure]
     */
    public function makeCustomMethodName(string $name, string $type): string
    {
        $nameKey = TextTransformer::new()->toCamelCase(strtolower($name));
        $customMethodName = $this->customMethodPrefix . $nameKey . '_' . $type;
        return $customMethodName;
    }

    /**
     * @param string $parameters
     * @return bool
     * #[Pure]
     */
    public function checkCustomFieldFileParameter(string $parameters): bool
    {
        $parsedParameters = explode(';', $parameters);
        $isValidParameters = true;
        if (
            $parsedParameters[0] !== ''
            && isset($parsedParameters[1])
            && is_numeric($parsedParameters[1])
        ) {
            $extensions = explode('|', $parsedParameters[0]);
            foreach ($extensions as $extension) {
                if (!ctype_alnum($extension)) {
                    $isValidParameters = false;
                }
            }
        } else {
            $isValidParameters = false;
        }
        return $isValidParameters;
    }

    /**
     * @param string $selectListOptions
     * @return array
     * #[Pure]
     */
    public function extractDropdownOptionsFromString(string $selectListOptions): array
    {
        $selectListOptions = trim($selectListOptions);
        $options = str_getcsv($selectListOptions);
        return $options;
    }

    /**
     * Return tag name for SOAP calls
     *
     * @param string $customFieldName
     * @return string
     * #[Pure]
     */
    public function makeSoapTagByName(string $customFieldName): string
    {
        return 'CustomField' . TextTransformer::new()->toCamelCase(strtolower($customFieldName));
    }

    /**
     * Return alias of column for custom data value
     * @param string $customFieldName
     * @return string
     * #[Pure]
     */
    public function makeFieldAlias(string $customFieldName): string
    {
        $alias = 'c' . DbTextTransformer::new()->toDbColumn($customFieldName);
        return $alias;
    }
}
