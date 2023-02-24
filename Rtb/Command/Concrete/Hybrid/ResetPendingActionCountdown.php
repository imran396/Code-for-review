<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Sam\Core\Constants;
use Sam\Rtb\Command\Concrete\Base\CommandBase;
use Sam\Rtb\Hybrid\HelpersAwareTrait;
use Sam\Rtb\State\PendingAction\PendingActionUpdaterCreateTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class ResetPendingActionCountdown
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class ResetPendingActionCountdown extends CommandBase
{
    use HelpersAwareTrait;
    use PendingActionUpdaterCreateTrait;
    use RtbCurrentWriteRepositoryAwareTrait;

    protected bool $isValidated = false;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function execute(): void
    {
        $rtbCurrent = $this->getRtbCurrent();
        if ($this->validate()) {
            // resets pending action countdown
            $rtbCurrent = $this->createPendingActionUpdater()
                ->update($rtbCurrent, $rtbCurrent->PendingAction);
            $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
        }
    }

    /**
     * We return in response Pending Action Seconds Left value to admin clerk consoles and current winning bidder
     */
    protected function createResponses(): void
    {
        if (!$this->isValidated) {
            $this->setResponses([]);
            return;
        }

        $rtbCurrent = $this->getRtbCurrent();
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_RESET_PENDING_ACTION_COUNTDOWN_S,
            Constants\Rtb::RES_DATA => [],
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;

        if ($rtbCurrent->PendingAction === Constants\Rtb::PA_SELECT_BUYER_BY_AGENT) {
            $responses[Constants\Rtb::RT_INDIVIDUAL] = [$rtbCurrent->BuyerUser, $responseJson];
        } elseif ($rtbCurrent->PendingAction === Constants\Rtb::PA_SELECT_GROUPED_LOTS) {
            $responses[Constants\Rtb::RT_INDIVIDUAL] = [$rtbCurrent->GroupUser, $responseJson];
        }
        $this->setResponses($responses);
    }

    /**
     * Validate command permissions
     * @return bool
     */
    protected function validate(): bool
    {
        $this->isValidated = true;
        $rtbCurrent = $this->getRtbCurrent();
        if ($rtbCurrent->PendingAction) {
            if ($this->getUserType() === Constants\Rtb::UT_BIDDER) {
                if ($rtbCurrent->PendingAction === Constants\Rtb::PA_SELECT_GROUPED_LOTS) {
                    if ($this->getEditorUserId() !== $rtbCurrent->GroupUser) {
                        $this->isValidated = false;
                    }
                } elseif ($rtbCurrent->PendingAction === Constants\Rtb::PA_SELECT_BUYER_BY_AGENT) {
                    if ($this->getEditorUserId() !== $rtbCurrent->BuyerUser) {
                        $this->isValidated = false;
                    }
                } else {
                    $this->isValidated = false;
                }
            } elseif (in_array($this->getUserType(), [Constants\Rtb::UT_VIEWER, Constants\Rtb::UT_PROJECTOR], true)) {
                $this->isValidated = false;
            }
        } else {
            $this->isValidated = false;
        }
        if (!$this->isValidated) {
            log_warning(
                "Unexpected command: \"Reset Pending Action Countdown\" rejected"
                . composeSuffix(
                    [
                        'a' => $rtbCurrent->AuctionId,
                        'li' => $rtbCurrent->LotItemId,
                        'u' => $this->getEditorUserId(),
                        'ut' => $this->getUserType(),
                        'pa' => $rtbCurrent->PendingAction,
                        'bu' => $rtbCurrent->BuyerUser,
                        'gu' => $rtbCurrent->GroupUser,
                    ]
                )
            );
        }
        return $this->isValidated;
    }
}
