<?php

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Core\Constants;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;

/**
 * Class Interest
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class Interest extends CommandBase implements RtbCommandHelperAwareInterface
{
    public function execute(): void
    {
        if ($this->cfg()->get('core->rtb->biddingInterest->enabled')) {
            $editorUser = $this->getUserLoader()->load($this->getEditorUserId());
            if (!$editorUser) {
                return;
            }

            if (
                !$this->checkConsoleSync()
                || !$this->checkRunningLot()
            ) {
                /**
                 * @var Sync $syncCommand
                 */
                $syncCommand = $this->getRtbCommandHelper()->createCommand('Sync');
                $syncCommand
                    ->enableOutOfSyncMessage(true)
                    ->runInContext($this);
                return;
            }

            $bidderInterestManager = $this->getRtbDaemon()->getBidderInterestManager();

            if (!$bidderInterestManager->hasInterested($this->getAuctionId(), $editorUser->Id)) {
                $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($editorUser->Id, true);
                $fullName = UserPureRenderer::new()->renderFullName($userInfo);
                $companyName = $userInfo->CompanyName;
                if (!empty($companyName)) {
                    $companyName = ',' . $companyName;
                }
                $userLabel = $this->bidderNum . '-' . $fullName . '(' . $editorUser->Username . $companyName . ')';
            } else {
                $userLabel = $bidderInterestManager->getInterestedAttribute($this->getAuctionId(), $editorUser->Id, 'lbl');
            }

            $bidderInterestManager->setInterested(
                $this->getAuctionId(),
                $editorUser->Id,
                ['ts' => time(), 'lbl' => $userLabel]
            );

            $data = [
                Constants\Rtb::RES_USER_ID => $editorUser->Id,
                Constants\Rtb::RES_INTEREST_USER_LABEL => $userLabel,
            ];
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_INTEREST_S,
                Constants\Rtb::RES_DATA => $data
            ];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_CLERK] = $responseJson;
            $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;
            $this->setResponses($responses);
        }
    }
}
