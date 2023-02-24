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

use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;

/**
 * Class OptionAnalyser
 * @package Sam\Details
 */
class OptionAnalyser extends CustomizableClass
{
    /** @var string */
    protected string $optionStart = '[';
    /** @var string */
    protected string $optionEnd = ']';
    /** @var string[] */
    protected array $stringQuotes = ['"', "'"];
    /** @var string */
    protected string $functionSeparator = ';';
    /** @var string */
    protected string $functionStart = '(';
    /** @var string */
    protected string $functionEnd = ')';
    /** @var string */
    protected string $argumentSeparator = ',';
    /** @var string|null */
    protected ?string $view = null;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function parseOptions(): array
    {
        $options = $this->splitRawOptions();
        foreach ($options as &$optionProps) {
            if ($optionProps['option'] === 'flt') {
                $optionProps['value'] = $this->normalizeString($optionProps['value']);
                $optionProps['value'] = $this->parseFilterFunctions($optionProps['value']);
            } elseif ($optionProps['option'] === 'fmt') {
                $optionProps['value'] = $this->normalizeString($optionProps['value']);
            }
        }
        return $options;
    }

    public function splitRawOptions(): array
    {
        $options = [];
        $startStringQuote = null;
        $startPos = null;
        $view = $this->getView();
        $viewChars = mb_str_split($view, 1, 'UTF-8');
        foreach ($viewChars as $i => $char) {
            if ($startPos !== null) {
                if ($this->isStringQuote($char)) {
                    if ($startStringQuote === null) {
                        $startStringQuote = $char;
                    } elseif ($startStringQuote === $char) {
                        if (
                            $i === 0
                            || $viewChars[$i - 1] !== '\\'
                        ) {
                            $startStringQuote = null;
                        }
                    }
                }
                if (
                    $startStringQuote === null
                    && $char === $this->optionEnd
                ) {
                    $start = $startPos + mb_strlen($this->optionStart);
                    $end = $i - mb_strlen($this->optionEnd);
                    $len = $end - $start + 1;
                    $raw = ArrayHelper::arrayToString($viewChars, $start, $len);
                    if (preg_match('/^(\w+)=(.*)$/u', $raw, $matches)) {
                        $options[] = ['option' => $matches[1], 'value' => $matches[2]];
                    }
                }
            }
            if (
                $startStringQuote === null
                && $char === $this->optionStart
            ) {
                $startPos = $i;
            }
        }
        return $options;
    }

    protected function parseFilterFunctions(string $full): array
    {
        $functionsRaw = [];
        $startStringQuote = null;
        $startPos = 0;
        $fullChars = mb_str_split($full, 1, 'UTF-8');
        $length = count($fullChars);
        foreach ($fullChars as $i => $char) {
            $char = $fullChars[$i];
            if ($this->isStringQuote($char)) {
                if ($startStringQuote === null) {
                    $startStringQuote = $char;
                } elseif ($startStringQuote === $char) {
                    if (
                        $i === 0
                        || $fullChars[$i - 1] !== '\\'
                    ) {
                        // it is end quote, so string is finished
                        $startStringQuote = null;
                    }
                }
            }
            if ($startStringQuote === null) {
                if ($char === $this->functionSeparator) {
                    $len = $i - $startPos;
                    $functionsRaw[] = ArrayHelper::arrayToString($fullChars, $startPos, $len);
                    $startPos = $i + 1; // next char
                } elseif ($i === $length - 1) {
                    $len = $i + 1 - $startPos;
                    $functionsRaw[] = ArrayHelper::arrayToString($fullChars, $startPos, $len);
                }
            }
        }

        $functions = [];
        foreach ($functionsRaw as $functionRaw) {
            $function = $signatureRaw = null;
            if (preg_match('/^(\w+)(.*)$/u', $functionRaw, $matches)) {
                $function = $matches[1];
                $signatureRaw = $matches[2];
            }

            $startStringQuote = false;
            $startPos = null;
            $signatureRawChars = mb_str_split($signatureRaw, 1, 'UTF-8');
            $argumentsRaw = [];
            foreach ($signatureRawChars as $i => $signatureChar) {
                if ($this->isStringQuote($signatureChar)) {
                    if (!$startStringQuote) {
                        $startStringQuote = true;
                    } else {
                        if (
                            $i === 0
                            || $signatureRawChars[$i - 1] !== '\\'
                        ) {
                            $startStringQuote = false;
                        }
                    }
                }
                if (!$startStringQuote) {
                    if ($signatureChar === $this->functionStart) {
                        $startPos = $i + 1;
                    } elseif (in_array($signatureChar, [$this->argumentSeparator, $this->functionEnd], true)) {
                        $argumentRaw = ArrayHelper::arrayToString($signatureRawChars, $startPos, $i - $startPos);
                        if ($argumentRaw !== '') {
                            $argumentsRaw[] = $argumentRaw;
                        }
                        $startPos = $i + 1;
                    }
                }
            }

            $arguments = [];
            foreach ($argumentsRaw as $argumentRaw) {
                $value = null;
                $argumentRawLowerCase = mb_strtolower($argumentRaw);
                if (in_array($argumentRawLowerCase, ['true', 'false'])) {
                    $value = $argumentRawLowerCase === 'true';
                } elseif (
                    $argumentRaw !== ''
                    && $this->isStringQuote($argumentRaw[0])
                ) {
                    $value = $this->normalizeString($argumentRaw);
                } elseif (is_numeric($argumentRaw)) {
                    // TODO: consider US Num formatting?
                    $value = $argumentRaw;
                } else {
                    $value = $argumentRaw;
                }
                $arguments[] = $value;
            }
            $functions[] = ['function' => $function, 'arguments' => $arguments];
        }
        return $functions;
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

    protected function isStringQuote(string $char): bool
    {
        return in_array($char, $this->stringQuotes, true);
    }

    /**
     * Remove quotes that enclose string
     */
    protected function normalizeString(string $value): string
    {
        if (
            $value !== ''
            && $this->isStringQuote($value[0])
        ) {
            $quote = $value[0];
            $length = mb_strlen($value) - 2;
            $value = mb_substr($value, 1, $length);
            $regExp = "/\\\\{$quote}/u";
            $value = preg_replace($regExp, $quote, $value);
        }
        return $value;
    }
}
