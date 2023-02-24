<?php
/**
 * Immutable value object stores data used for web rendering of config option
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

namespace Sam\Installation\Config\Edit\Render\WebData\Area\LocalConfig\Option;

/**
 * Class OptionWebData
 * @package
 */
final class LocalOptionWebData
{
    /** @var string */
    private string $title;
    /** @var string */
    private string $urlHash;
    /** @var string */
    private string $type;
    /** @var string */
    private string $value;
    /** @var string */
    private string $deleteButtonHtml;
    /** @var string */
    private string $deleteCheckboxHtml;

    /**
     * OptionWebData constructor.
     * @param string $title
     * @param string $urlHash
     * @param string $type
     * @param string $value
     * @param string $deleteButtonHtml
     * @param string $deleteCheckboxHtml
     */
    public function __construct(
        string $title,
        string $urlHash,
        string $type,
        string $value,
        string $deleteButtonHtml,
        string $deleteCheckboxHtml
    ) {
        $this->title = $title;
        $this->urlHash = $urlHash;
        $this->type = $type;
        $this->value = $value;
        $this->deleteButtonHtml = $deleteButtonHtml;
        $this->deleteCheckboxHtml = $deleteCheckboxHtml;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getUrlHash(): string
    {
        return $this->urlHash;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getDeleteButtonHtml(): string
    {
        return $this->deleteButtonHtml;
    }

    /**
     * @return string
     */
    public function getDeleteCheckboxHtml(): string
    {
        return $this->deleteCheckboxHtml;
    }
}
