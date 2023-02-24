<?php

/**
 * SAM-5018 : Refactor Email_Template to sub classes
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Apr 1, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Build;


/**
 * Interface EmailBuilderInterface
 * @package Sam\Email
 */
interface DataConverterInterface
{

    /**
     * @param $data
     */
    public function convert($data);
}