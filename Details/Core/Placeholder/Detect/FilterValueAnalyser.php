<?php
/**
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Feb 16, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Core\Placeholder\Detect;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;

/**
 * Class FilterValueAnalyser
 * @package Sam\Details
 */
class FilterValueAnalyser extends CustomizableClass
{
    /**
     * @var string[]
     */
    protected array $errorMessages = [];
    protected ?string $view = null;
    private array $argumentPropertyDefaults = ['type' => 'string', 'required' => false];

    /**
     * @var array[]
     */
    private array $specifications = [
        [
            'name' => 'Alpha',
            'args' => [
                'allowWhiteSpace' => ['type' => 'bool'],
                'locale' => ['type' => 'string'],
            ],
        ],
        [
            'name' => 'StripTags',
        ],
        [
            'name' => 'StripNewlines',
        ],
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Extract output filters
     */
    protected function extract(string $valueRaw): array|bool     // eg. StripTags;StripNewlines();Alpha(true,'en_Us')
    {
        // $signatures = [];
        do {
            $valueRaw = trim($valueRaw);
            if (preg_match('/^[a-z]+/u', $valueRaw, $matches)) {
                $filterName = $matches[0];
                $specification = $this->findSpecificationByName($filterName);
                if ($specification) {
                    $result = $this->extractSignature($valueRaw, $filterName);
                    if ($result === false) {
                        return false;
                    }

                    [, $valueRaw] = $result;
                    // $signatures[] = $signature;
                    $valueRaw = ltrim($valueRaw);
                    if (isset($valueRaw[0]) && str_starts_with($valueRaw, ',')) {
                        $valueRaw = mb_substr($valueRaw, 1);
                    }
                } else {
                    $this->addErrorMessage("Filter '{$filterName}' unknown");
                    return false;
                }
            } else {
                $this->addErrorMessage("Incorrect option format");
                return false;
            }
        } while ($valueRaw !== '');

        return $result;
    }

    protected function extractSignature(string $valueRaw, string $filterName): array|bool
    {
        if (preg_match("/^{$filterName}(\(([^)]*)\))?/u", $valueRaw, $matches)) {
            $full = $matches[0];
            $argumentsRaw = $matches[2] ?? null;    // eg. true, "en_US"
            $argumentValues = [];
            if ($argumentsRaw !== null) {
                $argumentValues = $this->parseArgumentsToValues($argumentsRaw, $filterName);
            }
            if ($argumentValues !== false) {
                $signature = ['name' => $filterName, 'args' => $argumentValues];
                $valueRaw = mb_substr($valueRaw, mb_strlen($full));
                return [$signature, $valueRaw];
            }
        }
        return false;
    }

    /**
     * TODO IM: Seems that this method is never used
     */
    protected function shift(string $valueRaw): array|bool|null    // eg. Alpha(true, "en_US")
    {
        $filter = null;
        $valueRaw = trim($valueRaw);
        foreach ($this->specifications as $specification) {
            $filterName = $specification['name'];
            if (preg_match("/^{$filterName}(\(([^)]*)\))?;/u", $valueRaw, $matches)) {
                $size = mb_strlen($matches[0]);
                $argumentsRaw = $matches[2] ?? null;    // eg. true, "en_US"
                $argumentValues = [];
                if ($argumentsRaw !== null) {
                    $argumentValues = $this->parseArgumentsToValues($argumentsRaw, $filterName);
                }
                if ($argumentValues !== false) {
                    $filter = ['name' => $filterName, 'args' => $argumentValues];
                    $valueRaw = mb_substr($valueRaw, $size);
                } else {
                    return false;
                }
            }
        }
        return $filter;
    }

    protected function parseArgumentsToValues(string $argumentsRaw, string $filterName): array|bool
    {
        $values = [];
        $argumentsRaw = trim($argumentsRaw);
        $specification = $this->findSpecificationByName($filterName);
        $argumentPropertyCollection = empty($specification['args']) ? [] : $specification['args'];
        foreach ($argumentPropertyCollection as $argumentName => $argumentProperties) {
            $isRequired = $this->isArgumentRequired($filterName, $argumentName);
            $type = $this->getArgumentType($filterName, $argumentName);
            $full = $value = $errorMessage = null;
            if (
                $isRequired
                && $argumentsRaw === ''
            ) {
                $errorMessage = "Required argument '{$argumentName}' missed";
            } elseif ($type === 'bool') {
                if (preg_match('/^(true|false|1|0)/u', $argumentsRaw, $matches)) {
                    $full = $matches[0];
                    $value = $full === 'true' || $full === '1';
                } else {
                    $errorMessage = "Argument '{$argumentName}' boolean type mismatch";
                }
            } elseif ($type === 'string') {
                if (in_array($argumentsRaw[0], ['\'', '"'])) {
                    $quoteChar = $argumentsRaw[0];
                    if (preg_match("/^{$quoteChar}(.*[^\\\]){$quoteChar}/u", $argumentsRaw, $matches)) {
                        $full = $matches[0];
                        $value = $matches[1];
                    }
                }
                if ($value === null) {
                    $errorMessage = "Argument '{$argumentName}' string type mismatch";
                }
            } elseif ($type === 'numeric') {
                if (
                    preg_match("/^[\d.]+/u", $argumentsRaw, $matches)
                    && is_numeric($matches[0])
                ) {
                    $full = $value = $matches[0];
                }
                if ($value === null) {
                    $errorMessage = "Argument '{$argumentName}' numeric type mismatch";
                }
            }

            if (!$errorMessage) {
                $values[$argumentName] = $value;
                $argumentsRaw = mb_substr($argumentsRaw, mb_strlen($full));
                $argumentsRaw = ltrim($argumentsRaw);
                if (
                    $argumentsRaw !== ''
                    && str_starts_with($argumentsRaw, ',')
                ) {
                    $argumentsRaw = mb_substr($argumentsRaw, 1);
                }
            } else {
                $this->addErrorMessage($errorMessage);
                return false;
            }
        }
        return $values;
    }

    private function findSpecificationByName(string $name): ?array
    {
        $specification = null;
        foreach ($this->specifications as $specification) {
            if ($specification['name'] === $name) {
                break;
            }
        }
        return $specification;
    }

    private function isArgumentRequired(string $filterName, string $argumentName): bool
    {
        return (bool)$this->getArgumentPropertyValue($filterName, $argumentName, 'required');
    }

    private function getArgumentType(string $filterName, string $argumentName): mixed
    {
        return $this->getArgumentPropertyValue($filterName, $argumentName, 'type');
    }

    private function getArgumentPropertyValue(string $filterName, string $argumentName, string $argumentPropertyName): mixed
    {
        $specification = $this->findSpecificationByName($filterName);
        return $specification['args'][$argumentName][$argumentPropertyName] ?? $this->argumentPropertyDefaults[$argumentPropertyName];
    }

    /**
     * Extract format of DATE type placeholder
     */
    protected function extractFormat(): ?string
    {
        return $this->extractNotEmptyString('fmt');
    }

    /**
     * Extract index of element of INDEXED_ARRAY type placeholder
     */
    protected function extractIndex(): ?int
    {
        return $this->extractIntPositive('idx');
    }

    /**
     * Extract image size value, available for placeholders of image tags and urls
     */
    protected function extractImageSize(): ?int
    {
        return $this->extractIntPositive('isz');
    }

    /**
     * Extract category level
     */
    protected function extractLevel(): ?int
    {
        return $this->extractIntPositive('lvl');
    }

    protected function extractIntPositive(string $optionKey): ?int
    {
        $value = null;
        if ($this->exist($optionKey)) {
            $valueRaw = $this->extractValueRaw($optionKey);
            if (NumberValidator::new()->isIntPositive($valueRaw)) {
                $value = (int)$valueRaw;
            } else {
                $this->errorMessages[] = "Option \"{$optionKey}\" value should be positive integer";
            }
        }
        return $value;
    }

    protected function extractNotEmptyString(string $optionKey): ?string
    {
        $value = null;
        if ($this->exist($optionKey)) {
            $valueRaw = $this->extractValueRaw($optionKey);
            if ((string)$valueRaw !== '') {
                $value = $valueRaw;
            } else {
                $this->errorMessages[] = "Option \"{$optionKey}\" value cannot be empty";
            }
        }
        return $value;
    }

    protected function exist(string $optionKey): bool
    {
        $isFound = false;
        $view = $this->getView();
        if (preg_match_all('/\[' . $optionKey . '=/u', (string)$view, $matches)) {
            if (count($matches) > 1) {
                $this->errorMessages[] = "Duplicated options are not allowed ({$optionKey})";
            } else {
                $isFound = true;
            }
        }
        return $isFound;
    }

    protected function extractValueRaw(string $optionKey): ?string
    {
        $valueRaw = null;
        $view = $this->getView();
        if (preg_match('/\[' . $optionKey . '=([^]]*)]/u', (string)$view, $matches)) {
            $valueRaw = $matches[1];
        }
        return $valueRaw;
    }

    protected function addErrorMessage(string $errorMessage): static
    {
        $this->errorMessages[] = $errorMessage;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getErrorMessages(): array
    {
        return $this->errorMessages;
    }

    public function getView(): ?string
    {
        return $this->view;
    }

    public function setView(string $view): static
    {
        $this->view = $view;
        return $this;
    }
}
