<?php
/**
 * Commission data-definition class, it's used in Dto field initialization and for soap wsdl.
 *
 * SAM-4413: Improve soap wsdl output by auto-discovery
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Jan 15, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Data;

/**
 * Class Commission
 * @package Sam\EntityMaker\Base\Data
 */
class Commission
{
    /**
     * @var float Start amount
     */
    public $Amount;
    /**
     * @var float Percentage amount portion
     */
    public $Percent;
}
