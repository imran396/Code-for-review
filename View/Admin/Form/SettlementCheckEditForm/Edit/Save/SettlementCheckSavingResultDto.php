<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           11-16, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementCheckEditForm\Edit\Save;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\Edit\Single\Update\Internal\Result\SettlementCheckEditingSavingResult;
use Sam\Settlement\Check\Action\Edit\Single\Validate\Result\SettlementCheckEditingValidationResult;

/**
 * Class SettlementCheckSavingResultDto
 * @package Sam\View\Admin\Form\SettlementCheckEditForm\Edit\Save
 */
class SettlementCheckSavingResultDto extends CustomizableClass
{
    /** @var SettlementCheckEditingValidationResult */
    public SettlementCheckEditingValidationResult $validationResult;
    /**
     * @var SettlementCheckEditingSavingResult|null
     * null - means validation failed
     */
    public ?SettlementCheckEditingSavingResult $savingResult;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        SettlementCheckEditingValidationResult $validationResult,
        ?SettlementCheckEditingSavingResult $savingResult = null
    ): static {
        $this->validationResult = $validationResult;
        $this->savingResult = $savingResult;
        return $this;
    }
}
