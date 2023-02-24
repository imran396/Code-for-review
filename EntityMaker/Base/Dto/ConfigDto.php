<?php
/**
 * Configuration settings and some additional data required for Validator and Producer services of entity-maker modules.
 *
 * SAM-8837: Lot item entity maker module structural adjustments for v3-5
 * SAM-3874 Refactor SOAP service and apply unit tests
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Sep 3, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Dto;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\Mode;

/**
 * Class Dto
 * @package Sam\EntityMaker\Base
 */
abstract class ConfigDto extends CustomizableClass
{
    public int $editorUserId;
    /**
     * Account of service's running context.
     */
    public ?int $serviceAccountId;
    /**
     * Account of visiting domain.
     */
    public int $systemAccountId;
    public Mode $mode;
    public array $presentedCsvColumns = [];
    public string $encoding = '';
    public bool $clearValues = false;
    public bool $isInputDtoReady = false;

    public ValidationStatus $validationStatus = ValidationStatus::NONE;

    /**
     * @param Mode $mode
     * @param int|null $editorUserId null when editor is not known, e.g on Sign Up
     * @param int|null $serviceAccountId null when service account cannot be defined in caller and should be detected in entity-maker.
     * @param int $systemAccountId
     * @return $this
     */
    public function construct(
        Mode $mode,
        ?int $editorUserId,
        ?int $serviceAccountId,
        int $systemAccountId
    ): static {
        $this->mode = $mode;
        $this->editorUserId = $editorUserId;
        $this->serviceAccountId = $serviceAccountId;
        $this->systemAccountId = $systemAccountId;
        return $this;
    }

    public function enableValidStatus(bool $isValid): static
    {
        $this->validationStatus = $isValid ? ValidationStatus::VALID : ValidationStatus::INVALID;
        return $this;
    }

    public function setValidationStatus(ValidationStatus $validationStatus): static
    {
        $this->validationStatus = $validationStatus;
        return $this;
    }
}
