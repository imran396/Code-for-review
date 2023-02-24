<?php
/**
 * PlaceBid data-definition class, it's used in Dto field initialization and for soap wsdl.
 *
 * SAM-6420: SOAP PlaceBid call - enhance result status output
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Oct 08, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Data;

/**
 * Class PlaceBidOutput
 * @package Sam\EntityMaker\Base\Data
 */
class PlaceBidOutput
{
    /**
     * @var string
     */
    public $Accepted;
    /**
     * @var float
     */
    public $Asking;
    /**
     * @var string
     */
    public $Currency;
    /**
     * @var float
     */
    public $Current;
    /**
     * @var float
     */
    public $Max;
    /**
     * @var string accepted|declined|too low|too high|outbid
     */
    public $Status;
    /**
     * @var string
     */
    public $StatusDetail;
}
