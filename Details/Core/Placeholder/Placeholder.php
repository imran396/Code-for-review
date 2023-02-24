<?php
/**
 * Object that contains placeholder information:
 * type - See, Constants\Placeholder for available types,
 * key (eg. "start_date"),
 * view (eg. "{start_date:Y-m-d H:i:s}"),
 * format (eg. "Y-m-d H:i:s")
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

namespace Sam\Details\Core\Placeholder;

/**
 * Class Placeholder
 * @package Sam\Details
 */
class Placeholder implements \Stringable
{
    /**
     * Placeholder key, eg. "start_date"
     * @var string|null
     */
    protected ?string $key = null;
    /**
     * Placeholder options, eg. [
     *  ["option" => "fmt", "value" => "Y-m-d"],
     *  ["option" => "flt",
     *   "functions" => ["function" => "StripTags", "arguments" => [true, "string", 128]]
     *  ], ...]
     * @var array
     */
    protected array $options = [];
    /**
     * Sub-placeholders of Composite placeholder
     * or one sub-placeholder for Begin-End block placeholder defines base placeholder of block with original type
     * @var Placeholder[]
     */
    protected array $subPlaceholders = [];
    /**
     * Placeholder type, see Constants\Placeholder
     * @var int|null
     */
    protected ?int $type = null;
    /**
     * Placeholder view with options, eg. "start_date[fmt=Y-m-d][flt=Length(5)]"
     * @var string|null
     */
    protected ?string $view = null;

    /**
     * Placeholder constructor.
     */
    public function __construct()
    {
    }

    public function getDecoratedView(): string
    {
        return self::decorateView($this->getView());
    }

    public static function decorateView(string $view): string
    {
        return "{" . $view . "}";
    }

    public static function cleanView(string $decoratedView): string
    {
        return rtrim(ltrim($decoratedView, '{'), '}');
    }

    public function __toString(): string
    {
        return composeSuffix(
            [
                'Key' => $this->getKey(),
                'Type' => $this->getType(),
                'View' => $this->getDecoratedView(),
                'Options' => $this->getOptions(),
            ]
        );
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key): static
    {
        $this->key = $key;
        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): static
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Return type depends on option name, e.g. "flt" results with array of filtering functions
     * @return string|array|bool|null
     */
    public function getOptionValue(string $name): mixed
    {
        $value = null;
        foreach ($this->options as $optionProps) {
            if ($optionProps['option'] === $name) {
                $value = $optionProps['value'];
                break;
            }
        }
        return $value;
    }

    /**
     * Check existence of option by name
     */
    public function hasOption(string $name): bool
    {
        $has = false;
        foreach ($this->options as $optionProps) {
            if ($name === $optionProps['option']) {
                $has = true;
                break;
            }
        }
        return $has;
    }

    /**
     * @return Placeholder[]
     */
    public function getSubPlaceholders(): array
    {
        return $this->subPlaceholders;
    }

    /**
     * @param Placeholder[] $subPlaceholders
     */
    public function setSubPlaceholders(array $subPlaceholders): static
    {
        $this->subPlaceholders = $subPlaceholders;
        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getView(): string
    {
        return (string)$this->view;
    }

    public function setView(string $view): static
    {
        $this->view = $view;
        return $this;
    }
}
