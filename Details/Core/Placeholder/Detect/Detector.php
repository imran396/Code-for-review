<?php
/**
 * Parses data to find placeholders
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

use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Core\Placeholder\Placeholder;

/**
 * Class Detector
 * @package Sam\Details
 */
class Detector extends CustomizableClass
{
    /** @var string */
    protected string $startDecorator = '{';
    /** @var string */
    protected string $endDecorator = '}';
    /** @var string */
    protected string $compositeSeparator = '|';
    /** @var string */
    protected string $logicalNotSign = '!';
    /** @var string[] */
    protected array $stringQuotes = ['"', "'"];
    /** @var array */
    protected array $allKeys = [];

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return all placeholder keys, that are used in template
     * @param string $template "{key1} .. {$key2} .. "
     * @param array $keysByType [<type1> => [<key1>, <key2>], ...]
     * @return Placeholder[]
     */
    public function detectActualPlaceholders(string $template, array $keysByType): array
    {
        foreach ($keysByType as $keys) {
            $this->allKeys = array_merge($this->allKeys, $keys);
        }

        $fullViews = $this->matchAllViews($template);
        $placeholders = [];
        foreach ($fullViews as $fullView) {
            $placeholders[] = $this->producePlaceholder($fullView, $keysByType);
        }
        return array_filter($placeholders);
    }

    /**
     * Detect all placeholder views and return them without decoration
     */
    protected function matchAllViews(string $template): array
    {
        $start = 0;
        $views = [];
        $templateChars = mb_str_split($template, 1, 'UTF-8');
        do {
            [$view, $position] = $this->matchNextView($templateChars, $start);
            if ($view !== null) {
                $start = $position + mb_strlen($view);
                $views[] = Placeholder::cleanView($view);
            }
        } while ($view !== null);
        return $views;
    }

    /**
     * @return array [<view>, <position>]
     */
    protected function matchNextView(array $templateChars, int $start): array
    {
        $startStringQuote = null;
        $startPos = null;
        $length = count($templateChars);
        for ($i = $start; $i < $length; $i++) {
            $char = $templateChars[$i];
            $prev = $i > 0 ? $templateChars[$i - 1] : null;
            if ($startPos !== null) {
                if (in_array($char, $this->stringQuotes, true)) {
                    if ($startStringQuote === null) {
                        $startStringQuote = $char;
                    } elseif ($startStringQuote === $char) {
                        if (
                            $i === 0
                            || $prev !== '\\'
                        ) {
                            // it is end quote, so string is finished
                            $startStringQuote = null;
                        }
                    }
                }
                if (
                    $startStringQuote === null
                    && $char === $this->endDecorator
                ) {
                    $view = ArrayHelper::arrayToString($templateChars, $startPos, $i - $startPos);
                    return [$view, $startPos];
                }
            }
            if (
                $startStringQuote === null
                && $char === $this->startDecorator
                && ($i === 0
                    || $prev !== '\\')
            ) {
                $startPos = $i + 1;
            }
        }
        return [null, null];
    }

    protected function producePlaceholder(string $fullView, array $keysByType): ?Placeholder
    {
        $isBeginEnd = false;
        $isLogicalNot = false;
        /** @var Placeholder[] $subPlaceholders */
        $subPlaceholders = [];
        $subViews = $this->splitViews($fullView);
        foreach ($subViews as $subView) {
            $type = null;
            $options = [];
            $key = preg_replace('/^([^[]+).*$/u', '$1', $subView);
            if (preg_match('/(__begin|__end)$/u', (string)$key)) {
                $key = preg_replace('/(__begin|__end)$/u', '', $key);
                $isBeginEnd = true;
                if ($key[0] === $this->logicalNotSign) {
                    $isLogicalNot = true;
                    $key = substr($key, 1);
                }
            }
            if (in_array($key, $this->allKeys, true)) {
                // available placeholder found
                foreach ($keysByType as $t => $keys) {
                    if (in_array($key, $keys, true)) {
                        $type = $t;
                        break;
                    }
                }
                if (!$isBeginEnd) {
                    $options = OptionAnalyser::new()
                        ->setView($subView)
                        ->parseOptions();
                }
                $subPlaceholders[] = (new Placeholder())
                    ->setType($type)
                    ->setKey($key)
                    ->setView($subView)
                    ->setOptions($options);
            } elseif ($subPlaceholders !== []) {
                // when available placeholder not found and this is composite placeholder case,
                // this one is considered as inline text
                $subPlaceholders[] = (new Placeholder())
                    ->setType(Constants\Placeholder::INLINE_TEXT)
                    ->setView($subView);
            } else {
                continue;
            }
        }
        if (
            $isBeginEnd
            && $subPlaceholders
        ) {
            $placeholder = (new Placeholder())
                ->setType(Constants\Placeholder::BEGIN_END)
                ->setKey($subPlaceholders[0]->getKey())
                ->setView($fullView)
                ->setSubPlaceholders($subPlaceholders)
                ->setOptions(
                    [
                        [
                            'option' => Constants\Placeholder::OPTION_BEGIN_END_LOGICAL_NOT,
                            'value' => $isLogicalNot,
                        ],
                    ]
                );
        } elseif (count($subPlaceholders) > 1) {
            $placeholder = (new Placeholder())
                ->setType(Constants\Placeholder::COMPOSITE)
                ->setView($fullView)
                ->setSubPlaceholders($subPlaceholders);
        } elseif (count($subPlaceholders) === 1) {
            $placeholder = $subPlaceholders[0];
        } else {
            $placeholder = null;
            $fullViewDecorated = Placeholder::decorateView($fullView);
            log_warning("Unknown placeholder {$fullViewDecorated}");
        }
        return $placeholder;
    }

    /**
     * If placeholder is Composite, then split to sub-views
     * If placeholder isn't Composite, then return it in array
     * @return string[]
     */
    protected function splitViews(string $fullView): array
    {
        $views = [];
        $startPos = 0;
        $startStringQuote = null;
        $fullViewChars = mb_str_split($fullView, 1, 'UTF-8');
        $length = count($fullViewChars);
        foreach ($fullViewChars as $i => $char) {
            if (in_array($char, $this->stringQuotes, true)) {
                if ($startStringQuote === null) {
                    $startStringQuote = $char;
                } elseif ($startStringQuote === $char) {
                    if (
                        $i === 0
                        || $fullViewChars[$i - 1] !== '\\'
                    ) {
                        // it is end quote, so string is finished
                        $startStringQuote = null;
                    }
                }
            }
            if ($startStringQuote === null) {
                if ($char === $this->compositeSeparator) {
                    $views[] = ArrayHelper::arrayToString($fullViewChars, $startPos, $i - $startPos);
                    $startPos = $i + 1;
                } elseif ($i === $length - 1) {
                    // last or sole sub-view
                    $views[] = ArrayHelper::arrayToString($fullViewChars, $startPos, $i - $startPos + 1);
                }
            }
        }
        return $views;
    }

//    /**
//     * Inline for optimization too often calls
//     * @param string $char
//     * @return bool
//     */
//    protected function isStringQuote($char): bool
//    {
//        $is = in_array($char, $this->stringQuotes, true);
//        return $is;
//    }
}
