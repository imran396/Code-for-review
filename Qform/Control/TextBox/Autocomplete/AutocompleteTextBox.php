<?php
/**
 * SAM-6928: Exhausted memory when editing user bidder ( return to page error )
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           01-30, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Qform\Control\TextBox\Autocomplete;


use QControl;
use QForm;
use QTextBox;

/**
 * Class AutocompleteTextBox
 * @package Sam\QForm\Control\TextBox
 */
class AutocompleteTextBox extends QTextBox
{
    public const CSS_CLASS = 'autocompleteTextBox';

    /**
     * Url for process search query.
     */
    public string $searchQueryUrl = '';

    /**
     * AutocompleteTextBox constructor.
     * @param QForm|QControl $parentObject
     * @param string $controlId
     */
    public function __construct($parentObject, string $controlId)
    {
        $this->Placeholder = 'Start typing';
        $this->CssClass = self::CSS_CLASS;
        parent::__construct($parentObject, $controlId);
    }
}
