<?php
/**
 * SAM-9890: Check Printing for Settlements: Implementation of printing content rendering
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Printing\Internal\Render;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class CheckFieldRendererFactory
 * @package Sam\Settlement\Check
 * @internal
 */
class CheckFieldRendererFactory extends CustomizableClass
{
    use SettingsManagerAwareTrait;

    protected const CAN_ABSENT_SETTING_COORD_X = [
        Constants\Setting::STLM_CHECK_ADDRESS_COORD_X,
        Constants\Setting::STLM_CHECK_MEMO_COORD_X
    ];

    protected const CAN_ABSENT_SETTING_COORD_Y = [
        Constants\Setting::STLM_CHECK_ADDRESS_COORD_Y,
        Constants\Setting::STLM_CHECK_MEMO_COORD_Y
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $fieldName
     * @param string $xCoordinateSetting
     * @param string $yCoordinateSetting
     * @param int $accountId
     * @return CheckFieldRenderer
     * #[Pure]
     */
    public function create(
        string $fieldName,
        string $xCoordinateSetting,
        string $yCoordinateSetting,
        int $accountId
    ): CheckFieldRenderer {
        $fieldPosition = $this->constructCheckFieldPosition($xCoordinateSetting, $yCoordinateSetting, $accountId);
        return CheckFieldRenderer::new()->construct($fieldName, $fieldPosition);
    }

    /**
     * @param string $xCoordinateSetting
     * @param string $yCoordinateSetting
     * @param int $accountId
     * @return CheckFieldPosition|null
     * #[Pure]
     */
    protected function constructCheckFieldPosition(
        string $xCoordinateSetting,
        string $yCoordinateSetting,
        int $accountId
    ): ?CheckFieldPosition {
        $sm = $this->getSettingsManager();
        $xCoordinate = Cast::toInt($sm->get($xCoordinateSetting, $accountId), Constants\Type::F_INT_POSITIVE);
        $yCoordinate = Cast::toInt($sm->get($yCoordinateSetting, $accountId), Constants\Type::F_INT_POSITIVE);
        if ($this->canAbsent($xCoordinate, $yCoordinate, $xCoordinateSetting, $yCoordinateSetting)) {
            return null;
        }

        $checkHeight = (int)$sm->get(Constants\Setting::STLM_CHECK_HEIGHT, $accountId);
        $checkPerPage = (int)$sm->get(Constants\Setting::STLM_CHECK_PER_PAGE, $accountId);
        return CheckFieldPosition::new()->construct($xCoordinate, $yCoordinate, $checkHeight, $checkPerPage);
    }

    /**
     * Returns true if $xCoordinate || $yCoordinate === null and they are assigned with "Address coordinates" or "Memo coordinates"
     * parameters (not required values for Settlement check printing).
     *
     * @param int|null $xCoordinate
     * @param int|null $yCoordinate
     * @param string $xCoordinateSetting
     * @param string $yCoordinateSetting
     * @return bool
     * #[Pure]
     */
    protected function canAbsent(
        ?int $xCoordinate,
        ?int $yCoordinate,
        string $xCoordinateSetting,
        string $yCoordinateSetting
    ): bool {
        $can = (
                in_array($xCoordinateSetting, self::CAN_ABSENT_SETTING_COORD_X, true)
                && $xCoordinate === null
            ) || (
                in_array($yCoordinateSetting, self::CAN_ABSENT_SETTING_COORD_Y, true)
                && $yCoordinate === null
            );
        return $can;
    }
}
