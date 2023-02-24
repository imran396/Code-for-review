<?php
/**
 * Template sample renderer
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
 * Class TemplateSampler
 * @package Sam\Details
 */
abstract class TemplateSampler extends CustomizableClass implements ConfigManagerAwareInterface
{
    /**
     * @var string[]
     */
    protected array $beginEndKeys = [];
    protected string $beginEndTpl = "%s\n%s: %s\n%s";
    /**
     * @var string[]
     */
    protected array $compositeViews = [];
    protected string $newLine = "\n";
    protected string $titleTpl = "Placeholders of '%s' type:";
    protected string $sectionTpl = "%s\n\n%s\n\n";
    /**
     * @var string[]
     */
    protected array $viewsWithOptions = [];

    public function render(): string
    {
        $output = '';
        $keysGroupedByType = $this->getConfigManager()->getKeysGroupedByType();
        foreach (Constants\Placeholder::$typeNames as $type => $title) {
            if (!empty($keysGroupedByType[$type])) {
                $content = $this->buildContent($keysGroupedByType[$type]);
                $title = sprintf($this->titleTpl, $title);
                $output .= $this->renderSection($title, $content);
            }
        }
        $output .= $this->renderBeginEndSection();
        $output .= $this->renderPlaceholderWithOptionSection();
        $output .= $this->renderCompositeSection();
        return $output;
    }

    public function setNewLine(string $newLine): static
    {
        $this->newLine = $newLine;
        return $this;
    }

    /**
     * @param string[] $keys
     */
    protected function buildContent(array $keys): string
    {
        $blocks = [];
        foreach ($keys as $key) {
            $isAvailable = $this->getConfigManager()->getOption($key, 'available') ?? true;
            if ($isAvailable) {
                $blocks[] = $key . ': ' . Placeholder::decorateView($key);
            }
        }
        return implode($this->newLine, $blocks);
    }

    protected function renderSection(string $title, string $content): string
    {
        return sprintf($this->sectionTpl, $title, $content);
    }

    protected function renderBeginEndSection(): string
    {
        if (
            !$this->beginEndKeys
            || !$this->getConfigManager()->isTypeEnabled(Constants\Placeholder::BEGIN_END)
        ) {
            return '';
        }
        // Build title
        $typeName = Constants\Placeholder::$typeNames[Constants\Placeholder::BEGIN_END];
        $title = "Example of {$typeName}:";
        // Build content
        $beginEndBlocks = [];
        foreach ($this->beginEndKeys as $key) {
            $begin = $this->getConfigManager()->makeBeginKey($key);
            $begin = Placeholder::decorateView($begin);
            $end = $this->getConfigManager()->makeEndKey($key);
            $end = Placeholder::decorateView($end);
            $label = $this->getConfigManager()->makeLangLabelKey($key);
            $label = Placeholder::decorateView($label);
            $view = Placeholder::decorateView($key);
            $beginEndBlocks[] = sprintf($this->beginEndTpl, $begin, $label, $view, $end);
        }
        $content = implode($this->newLine, $beginEndBlocks);
        // Build section
        $output = $this->renderSection($title, $content);
        return $output;
    }

    protected function renderPlaceholderWithOptionSection(): string
    {
        $output = '';
        if ($this->viewsWithOptions) {
            $title = "Options usage";
            $output = $this->renderSectionForArray($title, $this->viewsWithOptions);
        }
        return $output;
    }

    protected function renderCompositeSection(): string
    {
        $output = '';
        if (
            $this->compositeViews
            && $this->getConfigManager()->isTypeEnabled(Constants\Placeholder::COMPOSITE)
        ) {
            $typeName = Constants\Placeholder::$typeNames[Constants\Placeholder::COMPOSITE];
            $title = "Example of {$typeName}:";
            $output = $this->renderSectionForArray($title, $this->compositeViews);
        }
        return $output;
    }

    /**
     * @param string[] $views
     */
    protected function renderSectionForArray(string $title, array $views): string
    {
        $output = '';
        if ($views) {
            $blocks = [];
            foreach ($views as $view) {
                $blocks[] = $view . ': ' . Placeholder::decorateView($view);
            }
            $content = implode($this->newLine, $blocks);
            $output = $this->renderSection($title, $content);
        }
        return $output;
    }
}
