<?php
/**
 * SAM-6902: Decompose services from \Sam\Qform\QformHelpersAwareTrait to a separate independant <Servise>AwareTrait
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View;

use Sam\Qform\Js\ValueImporter;


/**
 * Trait JsValueImporterAwareTrait
 * @package Sam\View
 */
trait JsValueImporterAwareTrait
{
    protected ?ValueImporter $jsValueImporter = null;

    /**
     * @return ValueImporter
     */
    protected function getJsValueImporter(): ValueImporter
    {
        if ($this->jsValueImporter === null) {
            $this->jsValueImporter = ValueImporter::new();
        }
        return $this->jsValueImporter;
    }

    /**
     * @param ValueImporter $jsValueImporter
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setJsValueImporter(ValueImporter $jsValueImporter): static
    {
        $this->jsValueImporter = $jsValueImporter;
        return $this;
    }
}
