<?php
/**
 * Currency data-definition class, it's used in Dto field initialization and for soap wsdl.
 *
 * SAM-10548: Tax State field is missing in SOAP API document for CreateAuction and UpdateAuction calls.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Apr 25, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Data;

/**
 * Class State
 * @package Sam\EntityMaker\Base\Data
 */
class State
{
    /**
     * @var string <a href="/api/soap12?op=CountriesAndStates">2 character code</a> for US states, Canadian provinces
     */
    public $State;
}
