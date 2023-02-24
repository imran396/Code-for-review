<?php
/**
 * Immutable value object stores data used for web rendering of single validation error
 * at the top of edit web form.
 *
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-16, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Installation\Config\Edit\Render\WebData\Option;


/**
 * Class ValidationErrorWebData
 * @package Sam\Installation\Config
 */
final class ValidationErrorWebData
{
    /** @var string */
    protected string $optionTitle;
    /** @var string */
    protected string $urlHash;

    /**
     * ValidationErrorWebData constructor.
     * @param string $optionTitle
     * @param string $urlHash
     */
    public function __construct(string $optionTitle, string $urlHash)
    {
        $this->optionTitle = $optionTitle;
        $this->urlHash = $urlHash;
    }

    /**
     * @return string
     */
    public function getOptionTitle(): string
    {
        return $this->optionTitle;
    }

    /**
     * @return string
     */
    public function getUrlHash(): string
    {
        return $this->urlHash;
    }

}
