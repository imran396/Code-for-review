<?php
/**
 * Immutable value object to handle local option sections (0 - for not among global options, 1 - for local option in global config)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           2/4/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\WebData\Area\LocalConfig\Section;

use Sam\Installation\Config\Edit\Render\WebData\Area\LocalConfig\Option\LocalOptionWebData;

/**
 * Class LocalOptionSectionWebData
 * @package
 */
final class LocalOptionSectionWebData
{
    /** @var string */
    private string $title;
    /** @var LocalOptionWebData[][] */
    private array $optionWebDatas;
    /** @var int */
    private int $optionCount;
    /** @var string */
    protected string $cssClass;

    /**
     * OptionSectionWebData constructor.
     * @param string $title
     * @param LocalOptionWebData[][] $optionWebData
     * @param int $optionCount
     * @param string $cssClass
     */
    public function __construct(string $title, array $optionWebData, int $optionCount, string $cssClass)
    {
        $this->title = $title;
        $this->optionWebDatas = $optionWebData;
        $this->optionCount = $optionCount;
        $this->cssClass = $cssClass;
    }

    /**
     * @return string
     */
    public function getCssClass(): string
    {
        return $this->cssClass;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return LocalOptionWebData[][]
     */
    public function getData(): array
    {
        return $this->optionWebDatas;
    }

    /**
     * @return int
     */
    public function getOptionCount(): int
    {
        return $this->optionCount;
    }
}
