<?php
/**
 * SAM-6928 : Exhausted memory when editing user bidder, because of "Added By" control
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           08-22, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\Control\TextBox\Autocomplete;


use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;

/**
 * Class salesStaffAutocompleteTextBox
 * @package Sam\Qform\Control\TextBox\Autocomplete
 */
class SalesStaffAutocompleteTextBox extends AutocompleteTextBox
{
    use UrlBuilderAwareTrait;

    public function __construct($parentObject, string $controlId)
    {
        $this->searchQueryUrl = $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_REPORTS_SALES_USER_AUTOCOMPLETE)
        );
        $this->CssClass = "{$this->CssClass} salesStaffAutocomplete";
        parent::__construct($parentObject, $controlId);
    }
}
