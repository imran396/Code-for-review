<?php
/**
 * Repository for LotCategoryTemplate
 *
 * SAM-3692 : Lot Category related repositories  https://bidpath.atlassian.net/browse/SAM-3692
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           20 May, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\LotCategoryTemplate;

/**
 * Class LotCategoryTemplateReadRepository
 * @package Sam\Storage\ReadRepository\Entity\LotCategoryTemplate
 */
class LotCategoryTemplateReadRepository extends AbstractLotCategoryTemplateReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
