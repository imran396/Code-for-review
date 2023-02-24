<?php
/**
 * SAM-5761: Refactor Admin clerk console hybrid auction settings management to avoid POST request
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 31, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Exception;
use Sam\Account\Validate\AccountExistenceCheckerAwareTrait;
use Sam\Auction\Date\AuctionEndDateDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Rtb\Command\Concrete\Base\CommandBase;
use Sam\Rtb\Hybrid\HelpersAwareTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;

/**
 * This class represents Command for saving settings of hybrid auction
 *
 * Class SaveSetting
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class SaveSettings extends CommandBase
{
    use AccountExistenceCheckerAwareTrait;
    use AuctionEndDateDetectorCreateTrait;
    use AuctionWriteRepositoryAwareTrait;
    use HelpersAwareTrait;

    private int $extendTime = 0;
    private int $lotStartGapTime = 0;
    private bool $isAllowBiddingDuringStartGap = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function execute(): void
    {
        if (!$this->checkPermissions()) {
            $this->setErrorResponse('You don\'t have permission to perform this action');
            return;
        }

        if ($this->extendTime < $this->detectMinExtendTime()) {
            $this->setErrorResponse(
                sprintf(
                    'Extend Time interval should be positive integer not less %s seconds',
                    $this->detectMinExtendTime()
                )
            );
            return;
        }

        if ($this->extendTime > $this->detectMaxExtendTime()) {
            $this->setErrorResponse(
                sprintf(
                    'Extend Time interval should be positive integer not higher %s seconds',
                    $this->detectMaxExtendTime()
                )
            );
            return;
        }

        if (!NumberValidator::new()->isIntPositive($this->lotStartGapTime)) {
            $this->setErrorResponse('Lot Start Gap Time interval should be positive integer');
            return;
        }

        if ($this->lotStartGapTime < $this->detectMinLotStartGapTime()) {
            $this->setErrorResponse(
                sprintf(
                    'Lot Start Gap Time interval should be positive integer not less %s seconds',
                    $this->detectMinLotStartGapTime()
                )
            );
            return;
        }

        if ($this->lotStartGapTime > $this->detectMaxLotStartGapTime()) {
            $this->setErrorResponse(
                sprintf(
                    'Lot Start Gap Time interval should be positive integer not higher %s seconds',
                    $this->detectMaxLotStartGapTime()
                )
            );
            return;
        }

        $this->save();
    }

    /**
     * @param int|null $extendTime
     * @return static
     */
    public function setExtendTime(?int $extendTime): static
    {
        $this->extendTime = (int)$extendTime;
        return $this;
    }

    /**
     * @param int|null $lotStartGapTime
     * @return static
     */
    public function setLotStartGapTime(?int $lotStartGapTime): static
    {
        $this->lotStartGapTime = (int)$lotStartGapTime;
        return $this;
    }

    /**
     * @param bool $isAllowBiddingDuringStartGap
     * @return static
     */
    public function enableAllowBiddingDuringStartGap(bool $isAllowBiddingDuringStartGap): static
    {
        $this->isAllowBiddingDuringStartGap = $isAllowBiddingDuringStartGap;
        return $this;
    }

    /**
     * @return int
     */
    private function detectMinExtendTime(): int
    {
        return $this->cfg()->get('core->auction->hybrid->extendTime->minLimit');
    }

    /**
     * @return int
     */
    private function detectMaxExtendTime(): int
    {
        return $this->cfg()->get('core->auction->hybrid->extendTime->maxLimit');
    }

    /**
     * @return int
     */
    private function detectMinLotStartGapTime(): int
    {
        return $this->cfg()->get('core->auction->hybrid->lotStartGapTime->minLimit');
    }

    /**
     * @return int
     */
    private function detectMaxLotStartGapTime(): int
    {
        return $this->cfg()->get('core->auction->hybrid->lotStartGapTime->maxLimit');
    }

    /**
     * @return bool
     */
    private function checkPermissions(): bool
    {
        return true;
    }

    private function save(): void
    {
        try {
            $auction = $this->getAuction();
            $auction->ExtendTime = $this->extendTime;
            $auction->LotStartGapTime = $this->lotStartGapTime;
            $auction->AllowBiddingDuringStartGap = $this->isAllowBiddingDuringStartGap;
            if (
                !$auction->isClosed()
                && !$auction->isArchived()
            ) {
                $auction->EndDate = $this->createAuctionEndDateDetector()->detect($auction);
            }
            $this->getAuctionWriteRepository()->saveWithModifier($auction, $this->detectModifierUserId());
            $this->setSuccessResponse();
        } catch (Exception $e) {
            log_error($e->getMessage() . " " . $e->getTraceAsString());
            $this->setErrorResponse();
        }
    }

    private function setSuccessResponse(): void
    {
        $this->setResponse(
            [
                Constants\Rtb::RES_SUCCESS_MESSAGE => sprintf(
                    'Settings are saved %s',
                    composeSuffix(
                        [
                            'Extend Time' => $this->getAuction()->ExtendTime . ' sec',
                            'Lot Start Gap Time' => $this->getAuction()->LotStartGapTime . ' sec',
                            'Allow bidding during start gap' => $this->getAuction()->AllowBiddingDuringStartGap ? 'Yes'
                                : 'No'
                        ]
                    )
                )
            ]
        );
    }

    /**
     * @param string $message
     */
    private function setErrorResponse(string $message = 'Error occurred. Can\'t save settings'): void
    {
        $this->setResponse([Constants\Rtb::RES_ERROR_MESSAGE => $message]);
    }

    /**
     * @param $data
     */
    private function setResponse($data): void
    {
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SAVE_SETTINGS,
            Constants\Rtb::RES_DATA => $data
        ];
        $this->setResponses([Constants\Rtb::RT_SINGLE => json_encode($response)]);
    }
}
