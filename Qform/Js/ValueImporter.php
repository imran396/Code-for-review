<?php
/**
 * Class to inject server variable to js code
 *
 * SAM-3615: Reorganize js files
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 22, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\Js;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\ClearableInterface;
use RuntimeException;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;

/**
 * Class ValueImporter
 */
class ValueImporter extends CustomizableClass implements ClearableInterface
{
    use FormStateLongevityAwareTrait;

    private const DEFAULT_NAMESPACE = 'default';
    private const TRANSLATION_NAMESPACE = 'translation';

    /**
     * Data, that we want to inject into js code
     * @var array
     */
    private array $data = [];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Drop all values
     * @return static
     */
    public function clear(): static
    {
        $this->data = [];
        return $this;
    }

    /**
     * @param string $key
     * @param string $namespace
     * @return bool
     */
    public function existKey(string $key, string $namespace = self::DEFAULT_NAMESPACE): bool
    {
        $isFound = array_key_exists($namespace, $this->data)
            && array_key_exists($key, $this->data[$namespace]);
        return $isFound;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param string $namespace
     * @return bool
     */
    public function existKeyValue(string $key, mixed $value, string $namespace = self::DEFAULT_NAMESPACE): bool
    {
        $jsValue = $this->getJsValueObject($key, $namespace);
        $isFound = $jsValue && $jsValue->getValue() === $value;
        return $isFound;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function existTranslation(string $key): bool
    {
        $isFound = $this->existKey($key, self::TRANSLATION_NAMESPACE);
        return $isFound;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return static
     */
    public function injectTranslation(string $key, mixed $value): static
    {
        $this->injectValue($key, $value, self::TRANSLATION_NAMESPACE);
        return $this;
    }

    /**
     * @param array $values
     * @return static
     */
    public function injectTranslations(array $values): static
    {
        $this->injectValues($values, self::TRANSLATION_NAMESPACE);
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param string $namespace
     * @return static
     */
    public function injectValue(string $key, mixed $value, string $namespace = self::DEFAULT_NAMESPACE): static
    {
        $jsValue = new Value($value);
        if (empty($this->data[$namespace])) {
            $this->data[$namespace] = [];
        }
        $this->data[$namespace][$key] = $jsValue;
        return $this;
    }

    /**
     * @param array $values
     * @param string $namespace
     * @return static
     */
    public function injectValues(array $values, string $namespace = self::DEFAULT_NAMESPACE): static
    {
        foreach ($values as $key => $value) {
            $this->injectValue($key, $value, $namespace);
        }
        return $this;
    }

    /**
     * @param string $key
     * @param string $namespace
     * @return Value|null
     */
    protected function getJsValueObject(string $key, string $namespace = self::DEFAULT_NAMESPACE): ?Value
    {
        $result = null;
        if ($this->existKey($key, $namespace)) {
            $result = $this->data[$namespace][$key];
        }
        return $result;
    }

    /**
     * Render JS code to inject on a page with all variables
     * @return string
     */
    public function renderJs(): string
    {
        $js = '';
        $add = "sam.serverData.add(\"%s\", %s, \"%s\");\n";
        $addDef = "sam.serverData.add(\"%s\", %s);\n";
        foreach ($this->data as $namespace => $values) {
            /**
             * @var Value $value
             */
            foreach ($values as $key => $value) {
                if ($namespace === self::DEFAULT_NAMESPACE) {
                    $js .= sprintf($addDef, $key, $this->renderType($value->getValue()));
                } else {
                    $js .= sprintf($add, $key, $this->renderType($value->getValue()), $namespace);
                }
            }
        }

        $outputJs = <<<JS
window.addEventListener('DOMContentLoaded', function(event) { 
{$js}});
JS;

        $script = <<<HTML
<script>
{$outputJs}
</script>
HTML;
        return $script;
    }

    /**
     * @param $variable
     * @return string
     */
    private function renderType($variable): string
    {
        if (is_int($variable)) {
            $result = (string)$variable;
        } elseif (is_bool($variable)) {
            $result = $variable ? "true" : "false";
        } elseif (is_object($variable)) {
            $implements = class_implements($variable);
            if (!isset($implements['JsonSerializable'])) {
                throw new RuntimeException("The object must implement \JsonSerializable interface to be added");
            }
            $result = json_encode($variable);
        } elseif (is_array($variable)) {
            $result = json_encode($variable);
        } else {
            $variable = addslashes((string)$variable);
            $variable = preg_replace("/\n/", '\\n', $variable);
            $variable = preg_replace("/\r/", '\\r', $variable);
            $result = '"' . $variable . '"';
        }
        return $result;
    }
}
