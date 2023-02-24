<?php
/**
 * SAM-9669: Refactor \Qform_LotEditHelper
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Render;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class ConsignorRenderer
 * @package Sam\Consignor\Render
 */
class ConsignorRenderer extends CustomizableClass
{
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return consignor info string,
     * used in consignor text-box (at auction lots, lot edit)
     *
     * @param int|null $consignorUserId null if Entity does not have consignorUserId
     * or we can not receive it from POST-GET requests. See usage cases.
     * @return string
     */
    public function renderConsignorInfo(?int $consignorUserId): string
    {
        $select = [
            'u.customer_no',
            'u.username',
            'ui.company_name',
            'ui.first_name',
            'ui.last_name'
        ];
        $row = $this->getUserLoader()->loadSelected($select, $consignorUserId, true);
        if (!$row) {
            return '';
        }

        $output = $row['customer_no'] . ' - ' . $row['username'];
        $output .= ' ' . UserPureRenderer::new()->makeFullName($row['first_name'], $row['last_name']);
        $output .= $row['company_name']
            ? ' (' . $row['company_name'] . ')'
            : '';
        return $output;
    }
}
