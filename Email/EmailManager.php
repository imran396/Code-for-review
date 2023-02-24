<?php
/**
 * SAM-5018 copy current issue url Refactor Email_Template to sub classes
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 09, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Email;


use AuctionEmailTemplate;
use EmailTemplate;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Email\Build\EmailBuilderFactoryAwareTrait;
use Sam\Email\Load\EmailTemplateLoaderAwareTrait;
use Sam\Email\Queue\ActionQueueAwareTrait;
use Sam\Email\Queue\ActionQueueDto;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Class EmailManager
 * @package Sam\Email
 */
class EmailManager extends CustomizableClass
{
    use AccountAwareTrait;
    use ConfigRepositoryAwareTrait;
    use EmailTemplateLoaderAwareTrait;
    use AuctionAwareTrait;
    use EmailBuilderFactoryAwareTrait;
    use ActionQueueAwareTrait;

    protected EmailTemplate|AuctionEmailTemplate|null $emailTemplate = null;

    /**
     * @var string
     */
    protected string $emailKey = '';

    /**
     * @var bool
     */
    protected bool $isTest = false;

    /**
     * user.id who adds email to queue, null means current logged or system user
     * @var int|null
     */
    protected ?int $editorUserId = null;

    /**
     * optional unique identifier for email (by default TO~SUBJECT)
     * @var string|null
     */
    protected ?string $identifier = null;

    /**
     * optional group identifier
     * @var int|null
     */
    protected ?int $group = null;

    /**
     * default 1
     * @var int|null
     */
    protected ?int $maxAttempts = 1;

    /**
     * Possible values: ActionQueue::LOW, ActionQueue::MEDIUM or ActionQueue::HIGH
     * @var int|null
     */
    protected ?int $priority = Constants\ActionQueue::HIGH;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param $dto
     * @throws \Exception
     */
    public function register($dto): void
    {
        $emailKey = $this->isTest() ? Constants\EmailKey::SEND_TEST : $this->getEmailTemplate()->Key;
        $emailBuilder = $this->getEmailBuilderFactory()->getEmailBuilderByKey($emailKey);
        if (!$emailBuilder) {
            log_error("EmailBuilder is absent" . composeSuffix(['ley' => $emailKey]));
            return;
        }
        $emailBuilder->setAccount($this->getAccount())
            ->setAuction($this->getAuction())
            ->setEmailTemplate($this->getEmailTemplate());
        $email = $emailBuilder->build($dto);
        $this->addToActionQueue($email);
    }

    /**
     * @return AuctionEmailTemplate|EmailTemplate
     */
    private function createEmailTemplate(): AuctionEmailTemplate|EmailTemplate
    {
        $emailTemplate = $this->getEmailTemplateLoader()->loadByKeyAucId($this->getAuctionId(), $this->getEmailKey());
        if (!$this->emailTemplate) {
            $accountIdForEmailTpl = in_array($this->getEmailKey(), Constants\EmailKey::$mainAccountOnlyKeys, true)
                ? $this->cfg()->get('core->portal->mainAccountId')
                : $this->getAccountId();
            $emailTemplate = $this->getEmailTemplateLoader()->loadByKey($this->getEmailKey(), $accountIdForEmailTpl);
        }
        return $emailTemplate;
    }

    /**
     * Add email to ActionQueue
     *
     * @param Email $email
     * @return bool
     */
    protected function addToActionQueue(Email $email): bool
    {
        $auctionQueue = $this->getActionQueue();
        $auctionQueue->setEmailTemplate($this->getEmailTemplate());
        $auctionQueue->setIdentifier($this->getIdentifier());
        $auctionQueue->setGroup($this->getGroup());
        $auctionQueue->setMaxAttempts($this->getMaxAttempts());

        $dto = ActionQueueDto::new();
        $dto->setAccountId($this->getAccountId());
        $dto->setEmail($email);
        $dto->setEmailTemplate($this->getEmailTemplate());

        return $auctionQueue->add($dto, $this->getPriority(), $this->getEditorUserId());
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @return EmailManager
     */
    public function setPriority(int $priority): EmailManager
    {
        $this->priority = Cast::toInt($priority, Constants\ActionQueue::$priorities);
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailKey(): string
    {
        return $this->emailKey;
    }

    /**
     * @param string $emailKey
     * @return EmailManager
     */
    public function setEmailKey(string $emailKey): EmailManager
    {
        $this->emailKey = $emailKey;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTest(): bool
    {
        return $this->isTest;
    }

    /**
     * @param bool $isTest
     * @return EmailManager
     */
    public function enableTest(bool $isTest): EmailManager
    {
        $this->isTest = $isTest;
        return $this;
    }

    /**
     * @return AuctionEmailTemplate|EmailTemplate
     */
    public function getEmailTemplate(): AuctionEmailTemplate|EmailTemplate
    {
        if ($this->emailTemplate === null) {
            $this->emailTemplate = $this->createEmailTemplate();
        }
        return $this->emailTemplate;
    }

    /**
     * @param AuctionEmailTemplate|EmailTemplate $emailTemplate
     * @return EmailManager
     */
    public function setEmailTemplate(AuctionEmailTemplate|EmailTemplate $emailTemplate): EmailManager
    {
        $this->emailTemplate = $emailTemplate;
        return $this;
    }

    /**
     * @return int
     */
    public function getEditorUserId(): int
    {
        return $this->editorUserId;
    }

    /**
     * @param int $userId
     * @return EmailManager
     */
    public function setEditorUserId(int $userId): EmailManager
    {
        $this->editorUserId = Cast::toInt($userId, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return EmailManager
     */
    public function setIdentifier(string $identifier): EmailManager
    {
        $this->identifier = trim($identifier);
        return $this;
    }

    /**
     * @return int
     */
    public function getGroup(): int
    {
        return $this->group;
    }

    /**
     * @param int $group
     * @return EmailManager
     */
    public function setGroup(int $group): EmailManager
    {
        $this->group = Cast::toInt($group, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }

    /**
     * @param int $maxAttempts
     * @return EmailManager
     */
    public function setMaxAttempts(int $maxAttempts): EmailManager
    {
        $this->maxAttempts = Cast::toInt($maxAttempts, Constants\Type::F_INT_POSITIVE);
        return $this;
    }


}
