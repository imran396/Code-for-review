<?php

namespace Sam\Rtb\Console\Admin\Clerk\Live;

use DateTime;
use Exception;
use RtbCurrent;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\ClerkConsoleConstants;
use Sam\Core\Constants\Admin\ConsoleBaseConstants;
use Sam\Core\Math\Floating;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Rtb\Console\Admin\Internal\Load\DataProviderAwareTrait;
use Sam\Rtb\Console\Admin\Internal\Url\UrlProviderAwareTrait;
use Sam\Rtb\Console\Internal\AbstractConsoleBuilder;
use Sam\Rtb\Console\Internal\Load\GeneralDataProviderAwareTrait;
use Sam\Rtb\Control\GoToLot\Build\GoToLotListDataBuilderCreateTrait;
use Sam\Rtb\Control\Render\RtbControlBuilderCreateTrait;
use Sam\Rtb\CustomMessageHelper;
use Sam\Rtb\Increment\Load\AdvancedClerkingIncrementLoaderCreateTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class AdminLiveClerkConsoleBuilder
 */
class AdminLiveClerkConsoleBuilder extends AbstractConsoleBuilder
{
    use AdvancedClerkingIncrementLoaderCreateTrait;
    use DataProviderAwareTrait;
    use EditorUserAwareTrait;
    use GeneralDataProviderAwareTrait;
    use GoToLotListDataBuilderCreateTrait;
    use RtbControlBuilderCreateTrait;
    use SystemAccountAwareTrait;
    use UrlProviderAwareTrait;

    protected RtbCurrent $rtbCurrent;
    public string $clerkingStyle;
    public string $langOnline = 'Accept online bid!';
    public string $langFair = 'Fair warning';
    public string $langFloor = 'Post floor bid';
    public string $langUndoSnapshot = 'Undo';
    public string $langStartLot = 'Start lot';
    public string $langPauseLot = 'Pause lot';
    public string $langFloorPriority = 'Floor Priority';
    public string $langAbsenteePriority = 'Absentee Priority';
    public array $incrementListValues = [];

    /**
     * Get a AdminLiveClerkConsoleBuilder instance
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @return static
     */
    public function construct(int $auctionId): static
    {
        parent::construct($auctionId);
        $editorUserId = $this->getEditorUserId();
        $this->getDataProvider()->construct($auctionId, $editorUserId);
        $this->getGeneralDataProvider()->construct($auctionId, $editorUserId);
        $this->getUrlProvider()->construct($auctionId);
        $this->initControls();
        return $this;
    }

    public function initControls(): void
    {
        try {
            parent::initControls();

            $auction = $this->getAuction();
            $this->clerkingStyle = $auction->ClerkingStyle;
            if ($auction->isAdvancedClerking()) {
                $this->langOnline = 'Online';
                $this->langFair = 'Fair';
                $this->langFloor = 'Post floor';
                $this->langUndoSnapshot = 'Undo';
                $this->langStartLot = 'Start';
            }
            $this->rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($auction);
            $incrementsOptionValues = $this->buildIncrementsOptionValues($this->rtbCurrent);

            $this->internalInitControls($incrementsOptionValues);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @return string
     */
    public function loadScript(): string
    {
        $validator = $this->createRtbAuctionValidator();
        $success = $validator->validate($this->getAuctionId());
        $errorMessage = $validator->buildErrorMessageHtmlForAdmin();

        $auction = $this->getAuction();
        $dataProvider = $this->getDataProvider();
        $generalDataProvider = $this->getGeneralDataProvider();
        $urlProvider = $this->getUrlProvider();
        $userType = Constants\Rtb::UT_CLERK;
        $userCreatedOn = $this->getEditorUser()->CreatedOn
            ? (new DateTime($this->getEditorUser()->CreatedOn))->getTimestamp()
            : '';

        $jsImportValues = [
            // Basic values
            "accountId" => $this->getSystemAccountId(),
            "arrLiveBiddingCountdown" => $this->liveBiddingCountdowns,
            "auctionCurrencySign" => $generalDataProvider->detectDefaultCurrencySign(),
            "auctionExchangeRate" => 1,
            "auctionId" => $auction->Id,
            "auctionType" => $auction->AuctionType,
            "rtbCurrentDefaultIncrement" => $this->rtbCurrent->DefaultIncrement > 0 ? $this->rtbCurrent->DefaultIncrement : 1,
            "rtbCurrentEnableDecrement" => $this->rtbCurrent->EnableDecrement,
            "rtbCurrentIncrement" => $this->rtbCurrent->Increment > 0 ? $this->rtbCurrent->Increment : 0.,
            "rtbLiveHistoryServiceCommands" => $dataProvider->detectUndoCommands(),
            "sessionId" => $this->getSessionId(),
            "strClerkingStyle" => $this->clerkingStyle,
            "strCurrency" => $this->currencySign,
            "typeId" => $userType,
            "userCreatedOn" => $userCreatedOn,
            "userId" => $this->getEditorUserId(),

            // Installation config options
            "blnFloorBiddersDropdown" => $this->isFloorBiddersFromDropdown,
            "rtbAutoRefreshTimeout" => $this->cfg()->get('core->rtb->autoRefreshTimeout') * 1000,
            "rtbBidsBelowCurrent" => $this->cfg()->get('core->rtb->bidsBelowCurrent'),
            "rtbClerkBidderAddressEnabled" => $this->cfg()->get('core->rtb->clerk->bidder->address->enabled'),
            "rtbConnectionRetryCount" => $this->cfg()->get('core->rtb->connectionRetryCount'),
            "rtbContextMenuEnabled" => $this->cfg()->get('core->rtb->contextMenuEnabled'),
            "rtbMessageCenterRenderedMessageCount" => $this->cfg()->get('core->rtb->messageCenter->renderedMessageCount'),
            "rtbPingInterval" => $this->cfg()->get('core->rtb->ping->interval'),
            "rtbPingQualityIndicator" => $this->cfg()->get('core->rtb->ping->qualityIndicator')->toArray(),
            "rtbPingVariance" => $this->cfg()->get('core->rtb->ping->variance'),
            "rtbSoundVolume" => $this->cfg()->get('core->rtb->soundVolume'),

            // System parameters
            "blnClearMsgCenter" => $this->isClearMessageCenter,
            "blnTwentyMsgMax" => $this->isTwentyMessagesMax,
            "blnUsNumFormat" => $this->isUsNumberFormatting,
            "intDelayBlockSell" => $this->delayBlockSell,
            "intDelaySoldItem" => $this->delaySoldItem,

            // Console validation info
            "isError" => !$success,
            "strError" => $errorMessage,

            // Translation labels / TODO: why are they there, not in $jsImportTranslations?
            "strOnline" => $this->langOnline,
            "strFair" => $this->langFair,
            "strFloor" => $this->langFloor,

            // Complete urls and url templates
            "dataProviderBiddersUrl" => $urlProvider->buildRtbBidderDataUrl(),
            "reopenUrl" => $urlProvider->buildAuctionReopenUrl(),
            "runUrl" => $urlProvider->buildAuctionRunUrl(),
            "urlAddRtbMessage" => $urlProvider->buildMessageCenterAddUrlTemplate(),
            "urlCatalogLotM" => $urlProvider->buildLotDetailsUrlTemplate(),
            "urlCenterRtbMessage" => $urlProvider->buildMessageCenterDataUrlTemplate(),
            "urlDelRtbMessage" => $urlProvider->buildMessageCenterDeleteUrlTemplate(),
            "urlLotItemCatalog" => $urlProvider->buildLotCatalogDataUrlTemplate(),
            "urlLotItemGroup" => $urlProvider->buildLotGroupDataUrlTemplate(),
            "urlLotItemPreview" => $urlProvider->buildLotPreviewUrlTemplate(),
            "urlManageBidderInterest" => $urlProvider->buildBidderInterestDataUrlTemplate(),
            "urlManageRtbUsers" => $urlProvider->buildConnectedUsersDataUrlTemplate(),
            "urlRtbIncrementAdd" => $urlProvider->buildIncrementAddUrl(),
            "urlRtbIncrementDel" => $urlProvider->buildIncrementDeleteUrlTemplate(),
            "urlUserEdit" => $urlProvider->buildUserEditUrlTemplate(),
            "urlSoundOnlineBidIncomingOnAdmin" => $urlProvider->buildSoundOnlineBidIncomingOnAdmin($this->cfg()->get('core->portal->mainAccountId')),
            "urlSoundClickFromSoundManagerJsVendor" => $urlProvider->buildSoundClickFromSoundManagerJsVendor(),
            "urlSoundChimeFromSoundManagerJsVendor" => $urlProvider->buildSoundChimeFromSoundManagerJsVendor(),
            "wsHost" => $urlProvider->buildRtbdUri($userType),

            //rtbd connection status check
            'rtbdConnectionUrl' => $this->getRtbGeneralHelper()->getRtbPingUri(Constants\Rtb::UT_CLIENT, $this->getAuctionId()),
            'rtbdConnectionTestTimeout' => $this->cfg()->get('core->rtb->connectionTest->timeout'),
        ];
        $this->getJsValueImporter()->injectValues($jsImportValues);

        $tr = $this->getTranslator();
        $jsImportTranslations = [
            "bidderClientCantLoadData" => $tr->translateForRtb('BIDDERCLIENT_CANTLOADDATA', $auction),
            "bidderClientConnectionTerminated" => $tr->translateForRtb('BIDDERCLIENT_CONNECTION_TERMINATED', $auction),
            "bidderClientConsuccess" => $tr->translateForRtb('BIDDERCLIENT_CONSUCCESS', $auction),
            "bidderClientLotReopened" => $tr->translateForRtb('BIDDERCLIENT_LOTREOPENED', $auction),
            "bidderClientLotSoldToFloor" => $tr->translateForRtb('BIDDERCLIENT_LOTSOLDTOFLOOR', $auction),
            "bidderClientMsgFairWarn" => $tr->translateForRtb('BIDDERCLIENT_MSG_FAIRWARN', $auction),
            "bidderClientQuantityRtb" => $tr->translateForRtb('BIDDERCLIENT_QUANTITY_RTB', $auction),
            "bidderClientSelectGroup" => $tr->translateForRtb('BIDDERCLIENT_SELECTGROUP', $auction),
            "bidderClientSelectLot" => $tr->translateForRtb('BIDDERCLIENT_SELECT_LOT', $auction),
            "catalogBn" => $tr->translateForRtb('BIDDERCLIENT_BN', $auction),
            "catalogSoldThroughBuy" => $tr->translateForRtb('BIDDERCLIENT_SOLD_THROUGH_BUY', $auction),
            'rtbdConnectionFailedMessage' => sprintf(
                $tr->translateForRtb('BIDDERCLIENT_RTBD_CONNECTION_FAILED_MSG', $auction),
                $this->getRtbGeneralHelper()->getPublicHost(),
                $this->getRtbGeneralHelper()->getPublicPort()
            )
        ];
        $this->getJsValueImporter()->injectTranslations($jsImportTranslations);

        return ''; //Please, do not uncomment it. We refactor code. The new code is in assets/js/src/Admin/ManageAuctions/Run/AdminLive.js now
    }

    /**
     * Initialize controls collection for Admin Live clerk console
     * @param string $incrementsOptionValues
     */
    protected function internalInitControls(string $incrementsOptionValues): void
    {
        $generalDataProvider = $this->getGeneralDataProvider();
        $rtbControlCollection = $this->getRtbControlCollection();
        $rtbControlBuilder = $this->createRtbControlBuilder();
        $auction = $this->getAuction();

        $rtbControlCollection->add(
            $rtbControlBuilder->buildSpan(ConsoleBaseConstants::CID_LBL_STATUS)
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_START_AUCTION,
                ['class' => 'button', 'disabled' => 'disabled', 'html' => 'Start auction!', 'value' => 'Start Auction!',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_STOP_AUCTION,
                ['class' => 'button', 'html' => 'Stop Auction!', 'value' => 'Stop Auction!', 'style' => 'display:none;']
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_PAUSE_AUCTION,
                ['class' => 'button', 'html' => 'Pause Auction!', 'value' => 'Pause Auction!', 'style' => 'display:none;']
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_RESUME_AUCTION,
                ['class' => 'button', 'html' => 'Resume Auction!', 'value' => 'Resume Auction!', 'style' => 'display:none;']
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_RESET_BIDDING_HISTORY,
                ['class' => 'button', 'html' => 'Reset Bidding History!', 'value' => 'Reset Bidding History!', 'style' => 'display:none;']
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_CLEAR_AUCTION_HISTORY,
                ['class' => 'button', 'disabled' => 'disabled', 'html' => 'Clear!', 'value' => 'Clear Auction History!']
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_CLEAR_CHAT_LOG,
                ['class' => 'button', 'disabled' => 'disabled', 'html' => 'Clear!', 'value' => 'Clear Chat!']
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildSpan(
                ClerkConsoleConstants::CID_LBL_LOT_NO
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildSpan(
                ClerkConsoleConstants::CID_LBL_LOT_COUNT,
                ['html' => $generalDataProvider->detectHighestLotNum()]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildSpan(
                ClerkConsoleConstants::CID_LBL_LOT_NAME
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildLink(
                ClerkConsoleConstants::CID_LBL_LOT_DETAILS,
                ['html' => 'View lot detail screen', 'href' => 'javascript:void(0);']
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildSpan(
                ClerkConsoleConstants::CID_LBL_LOT_LOW_ESTIMATE
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildSpan(
                ClerkConsoleConstants::CID_LBL_LOT_HIGH_ESTIMATE
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildSpan(
                ClerkConsoleConstants::CID_LBL_LOT_RESERVE
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildSpan(
                ClerkConsoleConstants::CID_LBL_LOT_CURRENT_BID
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildSpan(
                ClerkConsoleConstants::CID_LBL_OWNER
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildSpan(
                ClerkConsoleConstants::CID_LBL_BIDDER_ADDRESS
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildText(
                ClerkConsoleConstants::CID_TXT_ASKING,
                ['class' => 'textbox', 'disabled' => 'disabled', 'maxlength' => '9', 'accesskey' => 'a',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_CHANGE_ASKING,
                ['class' => 'button', 'disabled' => 'disabled', 'html' => 'Change', 'value' => 'Change',]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_ACCEPT_BID,
                ['class' => 'button', 'disabled' => 'disabled', 'html' => $this->langOnline, 'value' => $this->langOnline, 'accesskey' => 'i',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_FLOOR_BID,
                ['class' => 'button', 'disabled' => 'disabled', 'html' => $this->langFloor, 'value' => $this->langFloor, 'accesskey' => 'f',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_FLOOR_PRIORITY,
                ['class' => 'button floor-priority', 'disabled' => 'disabled', 'html' => $this->langFloorPriority, 'value' => $this->langFloorPriority,]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_ABSENTEE_PRIORITY,
                ['class' => 'button absentee-priority', 'disabled' => 'disabled', 'html' => $this->langAbsenteePriority, 'value' => $this->langAbsenteePriority,]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_UNDO_SNAPSHOT,
                ['class' => 'button', 'disabled' => 'disabled', 'html' => $this->langUndoSnapshot, 'value' => $this->langUndoSnapshot, 'accesskey' => 'u',]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_FAIR_WARNING,
                ['class' => 'button', 'disabled' => 'disabled', 'html' => $this->langFair, 'value' => $this->langFair, 'accesskey' => 'w',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_PASS,
                ['class' => 'button', 'disabled' => 'disabled', 'html' => 'Pass', 'value' => 'Pass', 'accesskey' => 'p',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_SOLD,
                ['class' => 'button', 'disabled' => 'disabled', 'html' => 'Sold', 'value' => 'Sold', 'accesskey' => '4',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_START_LOT,
                ['class' => 'button', 'html' => $this->langStartLot, 'value' => $this->langStartLot, 'style' => 'display:none;']
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_PAUSE_LOT,
                ['class' => 'button', 'html' => $this->langPauseLot, 'value' => $this->langPauseLot, 'style' => 'display:none;']
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildCheckbox(
                ClerkConsoleConstants::CID_CHK_AUTO_START_LOT,
                ['checked' => 'checked', 'disabled' => 'disabled',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildCheckbox(
                ClerkConsoleConstants::CID_CHK_BIDDER_LOT,
                ['checked' => 'checked', 'disabled' => 'disabled',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildDiv(
                ClerkConsoleConstants::CID_LBL_AUCTION_HISTORY_MESSAGE,
                ['class' => 'content']
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildTextarea(
                ClerkConsoleConstants::CID_TXT_BIDDER_MESSAGE,
                ['class' => 'message-val', 'disabled' => 'disabled',]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_SEND_MESSAGE,
                ['class' => 'msg-button', 'disabled' => 'disabled', 'html' => 'Send', 'value' => 'Send',]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_SAVE_MESSAGE,
                ['class' => 'msg-button', 'disabled' => 'disabled', 'html' => 'Save as', 'value' => 'Save as',]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildText(
                ClerkConsoleConstants::CID_TXT_QUICK_MESSAGE_TITLE,
                ['class' => ClerkConsoleConstants::CLASS_TXT_MSG_TITLE, 'disabled' => 'disabled', 'maxlength' => 9,]
            )
        );


        $messageButtonHtml = '';
        $customMessageHelper = CustomMessageHelper::new();
        $rtbMessages = $customMessageHelper->getMessages($auction->AccountId);
        foreach ($rtbMessages as $rtbMessage) {
            $messageButtonHtml .= $customMessageHelper->renderMessageButton((int)$rtbMessage->Id);
        }
        $messageButtonHtml = str_replace('=', '~', $messageButtonHtml);
        $rtbControlCollection->add(
            $rtbControlBuilder->buildDiv(
                ClerkConsoleConstants::CID_LBL_QUICK_MESSAGE_BUTTONS,
                ['class' => 'buttons', 'html' => $messageButtonHtml,]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildSelect(
                ClerkConsoleConstants::CID_LST_BIDDER_MESSAGE,
                ['style' => 'width:196px;']
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildCheckbox(
                ClerkConsoleConstants::CID_CHK_FOLLOW_CATALOG,
                ['checked' => 'checked', 'disabled' => 'disabled']
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildRadio(
                ClerkConsoleConstants::CID_RAD_UPCOMING_CATALOG,
                ['checked' => 'checked', 'disabled' => 'disabled', 'value' => 'Upcoming',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildRadio(
                ClerkConsoleConstants::CID_RAD_PAST_CATALOG,
                ['disabled' => 'disabled', 'value' => 'Past',]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildTable(
                ClerkConsoleConstants::CID_LBL_CATALOG,
                ['style' => 'width:100%;',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildDiv(
                ClerkConsoleConstants::CID_LBL_LOT_IMAGE
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_PREV_LOT,
                ['class' => 'button btn-prev-lot', 'disabled' => 'disabled', 'html' => '<< Previous lot', 'value' => '<< Previous lot', 'style' => 'float:left;width:150px;']
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildSelect(
                ClerkConsoleConstants::CID_LST_UPCOMING,
                ['class' => 'button', 'disabled' => 'disabled', 'option' => $this->createGoToLotListDataBuilder()->build($auction), 'style' => 'width:320px;']
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_NEXT_LOT,
                ['class' => 'button btn-next-lot', 'disabled' => 'disabled', 'html' => 'Next lot >>', 'value' => 'Next lot >>', 'style' => 'float:right;width:150px;']
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_HID_MOVE_CONF
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildSpan(
                ClerkConsoleConstants::CID_LBL_CONNECTED_USER,
                ['class' => 'rtb-users']
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildDiv(
                ClerkConsoleConstants::CID_FRM_CONNECTED_USER,
                ['class' => 'rtb-users-list-data-area']
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildLink(
                ClerkConsoleConstants::CID_LNK_AUCTION_RESULT
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildLink(
                ClerkConsoleConstants::CID_LNK_AUCTION_HISTORY
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_GROUP_ADD,
                ['class' => 'button', 'value' => 'Confirm', 'html' => 'Confirm',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildSelect(
                ClerkConsoleConstants::CID_LST_GROUP_QTY
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_GROUP_OK,
                ['class' => 'button', 'value' => 'OK', 'html' => 'Ok',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_GROUP_CANCEL,
                ['class' => 'button', 'value' => 'Cancel', 'html' => 'Cancel',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildDiv(
                ClerkConsoleConstants::CID_LBL_GROUP_LOTS,
                ['class' => 'lot-choices-option',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildDiv(
                ClerkConsoleConstants::CID_LBL_GROUP_LOT_PREVIEW
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildSpan(
                ClerkConsoleConstants::CID_LBL_GROUP_PRICE
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_CONTINUE_SALE,
                ['value' => 'Continue Sale', 'html' => 'Continue Sale',]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildText(
                ClerkConsoleConstants::CID_TXT_BIDDER
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildHidden(
                ClerkConsoleConstants::CID_HID_BIDDER
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_BIDDER_OK,
                ['value' => 'OK', 'html' => 'OK',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_BIDDER_CANCEL,
                ['value' => 'Cancel', 'html' => 'Cancel',]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildSpan(
                ClerkConsoleConstants::CID_LBL_CURRENT_INC
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildLink(
                ClerkConsoleConstants::CID_LNK_REST_INC
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_ASK_INC1,
                ['class' => ClerkConsoleConstants::CLASS_BTN_INC, 'disabled' => 'disabled', 'html' => 'N/A',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_ASK_INC2,
                ['class' => ClerkConsoleConstants::CLASS_BTN_INC, 'disabled' => 'disabled', 'html' => 'N/A',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_ASK_INC3,
                ['class' => ClerkConsoleConstants::CLASS_BTN_INC, 'disabled' => 'disabled', 'html' => 'N/A',]
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_ASK_INC4,
                ['class' => ClerkConsoleConstants::CLASS_BTN_INC, 'disabled' => 'disabled', 'html' => 'N/A',]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildCheckbox(
                ClerkConsoleConstants::CID_CHK_AUTO_ACCEPT_BID,
                ['disabled' => 'disabled',]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildDiv(
                ClerkConsoleConstants::CID_LBL_ABSENTEE_BID_HIGH
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildDiv(
                ClerkConsoleConstants::CID_LBL_ABSENTEE_BID_SECOND
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildDiv(
                ClerkConsoleConstants::CID_LBL_ABSENTEE_SUGGEST_BID
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildDiv(
                ClerkConsoleConstants::CID_LBL_CLERK_NOTE
            )
        );
        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_CLERK_NOTE_OK,
                ['value' => 'OK', 'html' => 'OK',]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildTextarea(ClerkConsoleConstants::CID_TXT_GENERAL_NOTE)
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_GROUP_ALL_4_ONE,
                ['class' => 'button', 'disabled' => 'disabled', 'html' => 'Group(All4One)', 'value' => 'Group(All4One)', 'style' => 'width:110px;']
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_GROUP_X_ALL,
                ['class' => 'button', 'disabled' => 'disabled', 'html' => "Group (x the {$this->currencySign})", 'value' => "Group (x the {$this->currencySign})", 'style' => 'width:110px;']
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_GROUP_CHOICE_ALL,
                ['class' => 'button', 'disabled' => 'disabled', 'html' => 'Group(Choice)', 'value' => 'Group(choose)', 'style' => 'width:110px;']
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_GROUP_QTY_ALL,
                ['class' => 'button', 'disabled' => 'disabled', 'html' => 'Group(Qty)', 'value' => 'Group(Qty)', 'style' => 'width:110px;']
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildSpan(
                ClerkConsoleConstants::CID_LBL_GROUP_BY
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_CONTINUE_SALE_BUYER,
                ['value' => 'Continue Sale', 'html' => 'Continue Sale',]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildSpan(
                ClerkConsoleConstants::CID_LBL_BIDDER
            )
        );


        $rtbControlCollection->add(
            $rtbControlBuilder->buildDiv(
                ClerkConsoleConstants::CID_LST_BID
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildSelect(
                ClerkConsoleConstants::CID_LST_INCREMENT,
                ['multiple' => 'multiple', 'disabled' => 'disabled', 'option' => $incrementsOptionValues, 'onscroll' => 'return false;',]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildSelect(
                ClerkConsoleConstants::CID_LST_INCREMENT_DEFAULT,
                ['class' => 'select', 'disabled' => 'disabled', 'option' => $incrementsOptionValues,]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildText(
                ClerkConsoleConstants::CID_TXT_ADD_INC,
                ['class' => 'text', 'disabled' => 'disabled', 'value' => 'Add new',]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_DEL_INC,
                ['class' => 'button', 'disabled' => 'disabled', 'value' => 'Delete', 'html' => 'Delete',]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildDiv(
                ClerkConsoleConstants::CID_LBL_ERR_INC,
                ['class' => 'label']
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildCheckbox(
                ClerkConsoleConstants::CID_CHK_DECREMENT,
                ['disabled' => 'disabled']
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildCheckbox(
                ClerkConsoleConstants::CID_CHK_POST_1CLICK,
                ['disabled' => 'disabled']
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildSpan(
                ClerkConsoleConstants::CID_LBL_BID_CONFIRMATION
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_BID_CONFIRMATION_YES,
                ['value' => 'Yes', 'html' => 'Yes',]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_BID_CONFIRMATION_NO,
                ['value' => 'No', 'html' => 'No',]
            )
        );

        $rtbControlCollection->add(
            $rtbControlBuilder->buildDiv(
                ClerkConsoleConstants::CID_FRM_INTERESTED_BIDDER,
                ['class' => 'rtb-interested-bidders-list-data-area']
            )
        );

        $countdownCount = count($this->liveBiddingCountdowns) > 0 ? $this->liveBiddingCountdowns[0] : 'Sold';

        $rtbControlCollection->add(
            $rtbControlBuilder->buildButton(
                ClerkConsoleConstants::CID_BTN_BID_COUNTDOWN,
                ['class' => 'button bid-count', 'html' => $countdownCount, 'value' => $countdownCount,]
            )
        );
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return string
     */
    protected function buildIncrementsOptionValues(RtbCurrent $rtbCurrent): string
    {
        $incrementsOptionValues = '';
        $numberFormatter = $this->getNumberFormatter();
        $isDecrementEnabled = $rtbCurrent->EnableDecrement;

        $rtbCurrentIncrements = $this->createAdvancedClerkingIncrementLoader()
            ->loadEntities($rtbCurrent->AuctionId, $rtbCurrent->LotItemId);
        foreach ($rtbCurrentIncrements as $rtbCurrentIncrement) {
            $originalInc = $isDecrementEnabled ? (-1) * $rtbCurrentIncrement->Increment
                : $rtbCurrentIncrement->Increment;
            $incrementList = $numberFormatter->formatMoney($rtbCurrentIncrement->Increment);
            if ($isDecrementEnabled) {
                $incrementList = '-' . $incrementList;
            }
            $this->incrementListValues[] = ['formatted' => $incrementList, "original" => $originalInc];
            $incrementsOptionValues .= $incrementList . ':' . $incrementList . '|';
        }
        $incrementsOptionValues = rtrim($incrementsOptionValues, "|");

        // make sort
        usort(
            $this->incrementListValues,
            static function ($a, $b) {
                if (Floating::eq($a['original'], $b['original'])) {
                    return 0;
                }
                return Floating::lt($a['original'], $b['original']) ? -1 : 1;
            }
        );

        return $incrementsOptionValues;
    }
}
