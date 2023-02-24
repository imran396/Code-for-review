<?php
/**
 * Placeholder informational section rendering
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         May 9, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Core;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Core\Placeholder\Placeholder;

/**
 * Class HintRenderer
 * @package Sam\Details
 */
abstract class HintRenderer extends CustomizableClass implements ConfigManagerAwareInterface
{
    /**
     * @var string
     */
    protected string $beginEndKey = '';
    /**
     * @var string
     */
    protected string $compositeView = '';
    /**
     * Define inline help name (same as csv file name)
     * @var string
     */
    protected string $inlineHelpSection = '';
    /**
     * @var bool
     */
    protected bool $isOptionSection = true;
    /**
     * Draw each placeholder on new line
     * @var bool
     */
    protected bool $isSinglePlaceholderPerLine = false;

    public function render(): string
    {
        $output = '';
        $keysGroupedByType = $this->getConfigManager()->getKeysGroupedByType();
        foreach (Constants\Placeholder::$typeNames as $type => $title) {
            if ($this->getConfigManager()->hasAvailableKeysForType($type)) {
                $content = $this->buildHintContent($keysGroupedByType[$type]);
                $output .= $this->renderHintSection($title, $content);
            }
        }
        $output .= $this->renderBeginEndSection();
        $output .= $this->renderOptionSection();
        $output .= $this->renderCompositeSection();
        return $output;
    }

    /**
     * @noinspection PhpUnused
     */
    public function enableOptionSection(bool $enable): static
    {
        $this->isOptionSection = $enable;
        return $this;
    }

    /**
     * Draw each placeholder on new line
     */
    public function enableSinglePlaceholderPerLine(bool $isSinglePlaceholderPerLine): static
    {
        $this->isSinglePlaceholderPerLine = $isSinglePlaceholderPerLine;
        return $this;
    }

    /**
     * @param string[] $keys
     */
    protected function buildHintContent(array $keys): string
    {
        $placeholders = [];
        foreach ($keys as $key) {
            $isAvailable = $this->getConfigManager()->getOption($key, 'available') ?? true;
            if ($isAvailable) {
                $placeholderOutput = renderInlineHelp($key, $this->inlineHelpSection, $key);
                $placeholderOutput = $placeholderOutput ?? $key;
                $placeholders[] = Placeholder::decorateView($placeholderOutput);
            }
        }
        $glue = " ";
        if ($this->isSinglePlaceholderPerLine) {
            $glue = "<br/>";
        }
        return implode($glue, $placeholders);
    }

    protected function renderHintSection(string $title, string $content): string
    {
        return <<<HTML
<p><h3>{$title}</h3></p>
<p>{$content}</p>
<br />
HTML;
    }

    protected function renderBeginEndSection(): string
    {
        if (
            !$this->beginEndKey
            || !$this->getConfigManager()->isTypeEnabled(Constants\Placeholder::BEGIN_END)
        ) {
            return '';
        }
        $key = $this->beginEndKey;
        $begin = $this->getConfigManager()->makeBeginKey($key);
        $begin = Placeholder::decorateView($begin);
        $end = $this->getConfigManager()->makeEndKey($key);
        $end = Placeholder::decorateView($end);
        $label = $this->getConfigManager()->makeLangLabelKey($key);
        $label = Placeholder::decorateView($label);
        $view = Placeholder::decorateView($key);

        $content = <<<HTML
<pre>
{$begin}
&lt;tr&gt;
    &lt;td&gt;{$label}&lt;/td&gt;
    &lt;td&gt; - &lt;/td&gt;
    &lt;td&gt;{$view}&lt;/td&gt;
&lt;/tr&gt;
{$end}
</pre>
HTML;
        $output = $this->renderHintSection("BEGIN-END block decoration", $content);
        return _hl($output, $this->inlineHelpSection, 'begin_end_block_decoration', false);
    }

    protected function renderOptionSection(): string
    {
        if (!$this->isOptionSection) {
            return '';
        }

        $keysGroupedByType = $this->getConfigManager()->getKeysGroupedByType();
        $contents = [];
        if (!empty($keysGroupedByType[Constants\Placeholder::DATE])) {
            $dateKey = array_shift($keysGroupedByType[Constants\Placeholder::DATE]);
            $view = Placeholder::decorateView($dateKey . '[fmt=Y-m-d H:i:s]');
            $contents[] = '&lt;date placeholder&gt;[fmt=&lt;format&gt;] formats date placeholders. Eg. ' . $view;
        }

        if (!empty($keysGroupedByType[Constants\Placeholder::INDEXED_ARRAY])) {
            $dateKey = array_shift($keysGroupedByType[Constants\Placeholder::INDEXED_ARRAY]);
            $view = Placeholder::decorateView($dateKey . '[idx=0]');
            $contents[] = '&lt;indexed array placeholder&gt;[idx=&lt;index&gt;] defines index of value extracted from indexed array. Eg. ' . $view;
        }

        $keys = $this->getConfigManager()->getKeys();
        $dateKey = array_shift($keys);
        $view = Placeholder::decorateView($dateKey . '[flt=Length(5)]');
        $contents[] = '&lt;placeholder&gt;[flt=Length(&lt;length&gt;)] limits value length for any placeholder. Eg. ' . $view;
        $content = implode("<br />", $contents);
        return $this->renderHintSection("Placeholder Options", $content);
    }

    protected function renderCompositeSection(): string
    {
        if (!$this->compositeView) {
            return '';
        }
        $content = Placeholder::decorateView($this->compositeView)
            . " - composite placeholder can use options and can be ended with inline text";
        return $this->renderHintSection("Composite placeholder example", $content);
    }
}
