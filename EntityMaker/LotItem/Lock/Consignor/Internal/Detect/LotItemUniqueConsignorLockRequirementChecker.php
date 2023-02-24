<?php
/**
 * SAM-10625: Supply uniqueness for user fields: username
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Lock\Consignor\Internal\Detect;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\LotItem\Lock\Consignor\Internal\Detect\LotItemUniqueConsignorLockRequirementCheckingResult as Result;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\User\Validate\UserExistenceCheckerAwareTrait;

/**
 * @package Sam\EntityMaker\LotItem
 */
class LotItemUniqueConsignorLockRequirementChecker extends CustomizableClass
{
    use UserExistenceCheckerAwareTrait;
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function check(Mode $mode, ?string $consignorName): Result
    {
        $result = Result::new()->construct($mode->name, $consignorName);
        if (!$consignorName) {
            return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_CONSIGNOR_NAME_IS_ABSENT);
        }

        if (!$this->shouldAutoCreateConsignor($mode)) {
            return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_AUTO_CREATE_CONSIGNOR_DISABLED);
        }

        if ($this->getUserExistenceChecker()->existByUsername($consignorName)) {
            return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_CONSIGNOR_ALREADY_EXISTS);
        }

        return $result->addSuccess(Result::OK_LOCK_BECAUSE_CONSIGNOR_WILL_BE_CREATED);
    }

    public function shouldAutoCreateConsignor(Mode $mode): bool
    {
        return match ($mode) {
            Mode::CSV => $this->cfg()->get('core->csv->lot->autoCreateConsignor'),
            Mode::SOAP => $this->cfg()->get('core->soap->lot->autoCreateConsignor'),
            default => false
        };
    }
}
