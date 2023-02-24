<?php
/**
 * Premium data-definition class, it's used in Dto field initialization and for soap wsdl.
 *
 * SAM-10273: Entity locations: Implementation (Dev)
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Feb 14, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Data;

/**
 * Class Premium
 * @package Sam\EntityMaker\Base\Data
 */
class Location
{
    /**
     * @var string
     */
    public $Address;
    /**
     * @var string
     */
    public $City;
    /**
     * @var string
     */
    public $County;
    /**
     * @var string <a href="/api/soap12?op=CountriesAndStates">2 character country Code</a>
     */
    public $Country;
    /**
     * @var string
     */
    public $Logo;
    /**
     * @var string <a href="/api/soap12?op=CountriesAndStates">2 character code</a> for US states, Canadian provinces or Mexico states
     */
    public $State;
    /**
     * @var string
     */
    public $Zip;
}
