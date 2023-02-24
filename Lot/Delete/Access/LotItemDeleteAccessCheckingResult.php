<?php
/**
 * SAM-9129: Verify lot item deleting operation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Delete\Access;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotItemDeleteAccessCheckingResult
 * @package Sam\Lot\Delete\Access
 */
class LotItemDeleteAccessCheckingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_DENIED_FOR_ANONYMOUS = 1;
    public const ERR_TARGET_LOT_ITEM_ID_UNDEFINED = 2;
    public const ERR_TARGET_LOT_ITEM_NOT_FOUND = 3;
    public const ERR_EDITOR_USER_NOT_FOUND = 4;
    public const ERR_DENIED_BECAUSE_NOT_ADMIN_AND_NOT_CONSIGNOR = 5;
    public const ERR_DENIED_FOR_CONSIGNOR_BECAUSE_ITEM_DELETE_BY_CONSIGNOR_RESTRICTED = 6;
    public const ERR_DENIED_FOR_CONSIGNOR_BECAUSE_NOT_OWNER = 7;
    public const ERR_DENIED_FOR_REGULAR_ADMIN_OF_DIFFERENT_ACCOUNT = 8;

    public const OK_ADMIN_AND_LOT_ACCOUNTS_MATCH = 11;
    public const OK_CROSS_DOMAIN_ADMIN_CAN_DELETE_ANY_LOT = 12;
    public const OK_CONSIGNOR_CAN_DELETE_OWN_LOT = 13;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_DENIED_FOR_ANONYMOUS => 'Denied for anonymous',
        self::ERR_TARGET_LOT_ITEM_ID_UNDEFINED => 'Target lot item id not defined',
        self::ERR_TARGET_LOT_ITEM_NOT_FOUND => 'Target lot item not found',
        self::ERR_EDITOR_USER_NOT_FOUND => 'Editor user not found',
        self::ERR_DENIED_BECAUSE_NOT_ADMIN_AND_NOT_CONSIGNOR => 'Denied, because editor is not admin and not consignor',
        self::ERR_DENIED_FOR_CONSIGNOR_BECAUSE_ITEM_DELETE_BY_CONSIGNOR_RESTRICTED => 'Denied for consignor, because item deleting by consignor is restricted',
        self::ERR_DENIED_FOR_CONSIGNOR_BECAUSE_NOT_OWNER => 'Denied for consignor, because he is not item owner',
        self::ERR_DENIED_FOR_REGULAR_ADMIN_OF_DIFFERENT_ACCOUNT => 'Denied for regular admin of different account',
    ];

    /** @var string[] */
    protected const SUCCESS_MESSAGES = [
        self::OK_ADMIN_AND_LOT_ACCOUNTS_MATCH => 'Allowed for admin to delete lot of the own account',
        self::OK_CROSS_DOMAIN_ADMIN_CAN_DELETE_ANY_LOT => 'Cross-domain admin can delete any lot',
        self::OK_CONSIGNOR_CAN_DELETE_OWN_LOT => 'Allowed for consignor to delete own lot',
    ];

    /** @var int|null */
    private ?int $taregetLotItemId;
    /** @var int|null */
    private ?int $editorUserId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $targetLotItemId
     * @param int|null $editorUserId
     * @return $this
     */
    public function construct(?int $targetLotItemId, ?int $editorUserId): static
    {
        $this->taregetLotItemId = $targetLotItemId;
        $this->editorUserId = $editorUserId;
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES);
        return $this;
    }

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addSuccess(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addSuccess($code, $message);
        return $this;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstErrorCode();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function successCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstSuccessCode();
    }

    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    public function statusCode(): ?int
    {
        if ($this->hasSuccess()) {
            return $this->successCode();
        }
        if ($this->hasError()) {
            return $this->errorCode();
        }
        return null;
    }

    public function logData(): array
    {
        $logData = [
            'li' => $this->taregetLotItemId,
            'editor u' => $this->editorUserId,
        ];
        if ($this->hasError()) {
            $logData += [
                'error code' => $this->errorCode(),
                'error message' => $this->errorMessage()
            ];
        }
        if ($this->hasSuccess()) {
            $logData += [
                'success code' => $this->successCode(),
                'success message' => $this->successMessage()
            ];
        }
        return $logData;
    }

}
