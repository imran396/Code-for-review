<?php

/**
 * SAM-4924: Report a problem test link adjustment related to rtbd public path
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Июнь 03, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feedback\LiveSale;

use Auction;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Email\Queue\ActionQueue;
use Sam\Email\Queue\ActionQueueDto;
use Sam\Email\Email;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Class LiveSaleFeedbackService
 * $this->sendEmail() main class method.
 *
 * @package Sam\Feedback\LiveSale
 */
class LiveSaleFeedbackService extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionRendererAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use RtbGeneralHelperAwareTrait;
    use SettingsManagerAwareTrait;

    private const EMAIL_TEMPLATE =
        'Timestamp: %s<br/>' .
        'URL: %s<br/>' .
        'Server IP: %s<br/>' .
        'Test: %s<br/>' .
        'User\'s browser, version & OS: %s<br/>' .
        'User\'s IP Address: %s<br/>' .
        'User Info: %s<br/>' .
        'User message: ===================================================<br/>' .
        '%s<br />' .
        '===================================================';

    /**
     * Url string
     * @var string
     */
    protected string $url = '';

    /**
     * Test url string
     * @var string
     */
    protected string $testUrl = '';


    /**
     * Server IP address
     * @var string
     */
    protected string $serverIp = '';

    /**
     * User Remote IP address
     * @var string
     */
    protected string $userIp = '';

    /**
     * User browser OS string
     * @var string
     */
    protected string $userBrowserOs = '';

    /**
     * User info string
     * @var string
     */
    protected string $userNameInfo = '';

    /**
     * Reply to string
     * @var string
     */
    protected string $replyTo = '';

    /**
     * Report message
     * @var string
     */
    protected string $reportMessage = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Sending email. Main class method.
     * @param int $editorUserId
     */
    public function sendEmail(int $editorUserId): void
    {
        $auction = $this->getAuction();

        $name = $this->getAuctionRenderer()->renderName($auction);
        $subject = $auction ? 'Auction: ' . $name . ' ' : '';
        $supportEmail = (string)$this->getSettingsManager()->get(Constants\Setting::SUPPORT_EMAIL, $auction->AccountId);
        $bcc = $this->makeBcc($auction, $supportEmail);
        $htmlBody = $this->makeHtmlBody();

        $email = Email::new();
        $email->setTo($this->cfg()->get('core->rtb->feedbackEmail'));
        $email->setSubject('Report auction problem ' . $subject);
        if ($bcc) {
            $email->setBcc($bcc);
        }
        $email->setFrom($supportEmail);
        $email->setReplyTo($this->replyTo);
        $email->setHtmlBody($htmlBody);

        $dto = ActionQueueDto::new();
        $dto->setEmail($email);
        $dto->setAccountId($auction->AccountId);
        ActionQueue::new()->add($dto, Constants\ActionQueue::MEDIUM, $editorUserId);
    }

    /**
     * Get url
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Set url
     * @param string $url
     * @return static
     */
    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get test url
     * @return string
     */
    public function getTestUrl(): string
    {
        return $this->testUrl;
    }

    /**
     * Set test url
     * @param string $url
     * @return static
     */
    public function setTestUrl(string $url): static
    {
        $this->testUrl = $url;
        return $this;
    }

    /**
     * Get server IP address
     * @return string
     */
    public function getServerIp(): string
    {
        return $this->serverIp;
    }

    /**
     * Set server IP address
     * @param string $ip
     * @return static
     */
    public function setServerIp(string $ip): static
    {
        $this->serverIp = $ip;
        return $this;
    }

    /**
     * Get user remote IP address
     * @return string
     */
    public function getUserIp(): string
    {
        return $this->userIp;
    }

    /**
     * Set user remote IP address
     * @param string $ip
     * @return static
     */
    public function setUserIp(string $ip): static
    {
        $this->userIp = $ip;
        return $this;
    }

    /**
     * Get user browser OS info
     * @return string
     */
    public function getUserBrowserOs(): string
    {
        return $this->userBrowserOs;
    }

    /**
     * Set user browser OS info
     * @param string $os
     * @return static
     */
    public function setUserBrowserOs(string $os): static
    {
        $this->userBrowserOs = $os;
        return $this;
    }

    /**
     * Get user name info
     * @return string
     */
    public function getUserNameInfo(): string
    {
        return $this->userNameInfo;
    }

    /**
     * Set user info
     * @param string $info
     * @return static
     */
    public function setUserNameInfo(string $info): static
    {
        $this->userNameInfo = $info;
        return $this;
    }

    /**
     * Get reply to string
     * @return string
     */
    public function getReplyTo(): string
    {
        return $this->replyTo;
    }

    /**
     * Set reply to string
     * @param string $to
     * @return static
     */
    public function setReplyTo(string $to): static
    {
        $this->replyTo = $to;
        return $this;
    }

    /**
     * Get report message
     * @return string
     */
    public function getReportMessage(): string
    {
        return $this->reportMessage;
    }

    /**
     * Set report message
     * @param string $message
     * @return static
     */
    public function setReportMessage(string $message): static
    {
        $this->reportMessage = $message;
        return $this;
    }

    /**
     * Html body maker
     * @param bool $isExternalTestUrl
     * @return string
     */
    public function makeHtmlBody(bool $isExternalTestUrl = false): string
    {
        $testUrl = $isExternalTestUrl ? $this->testUrl : $this->makeTestUrl();
        $dateIso = $this->getCurrentDateUtcIso();

        $htmlBody = sprintf(
            self::EMAIL_TEMPLATE,
            $dateIso,
            $this->getUrl(),
            $this->getServerIp(),
            $testUrl,
            $this->getUserBrowserOs(),
            $this->getUserIp(),
            $this->getUserNameInfo(),
            $this->getReportMessage()
        );

        return $htmlBody;
    }

    /**
     * Bcc maker
     * @param Auction|null $auction
     * @param string $supportEmail
     * @return string
     */
    protected function makeBcc(?Auction $auction, string $supportEmail): string
    {
        $bcc = $supportEmail;
        if (
            $auction
            && $auction->Email
        ) {
            $bcc .= ($bcc ? ',' : '') . $auction->Email;
        }
        return $bcc;
    }

    /**
     * Making test Url string
     * @return string
     */
    protected function makeTestUrl(): string
    {
        $rtbGeneralHelper = $this->getRtbGeneralHelper();

        $path = $rtbGeneralHelper->getPublicPath(Constants\Rtb::UT_CLIENT) . '/ping';
        $path = preg_replace('|//|', '/', $path);
        $port = in_array($rtbGeneralHelper->getPublicPort(), [80, 443], true)
            ? '' // skip standard ports from url 80, 443
            : ':' . $rtbGeneralHelper->getPublicPort();

        $url = 'http' . ($this->cfg()->get('core->rtb->server->wss') ? 's' : '')
            . '://' . $rtbGeneralHelper->getPublicHost()
            . $port
            . $path;

        return $url;
    }

}
