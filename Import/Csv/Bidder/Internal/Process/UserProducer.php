<?php
/**
 * SAM-3796: Bidder upload into auction
 * SAM-9366: Refactor Sam\Bidder\AuctionBidder\CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Bidder\Internal\Process;

use Email_Template;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\EntityMaker\User\Save\UserMakerProducerCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\User\Save\UserCustomerNoApplierCreateTrait;
use Sam\User\Signup\Verify\Prepare\SignupVerificationPreparerCreateTrait;
use User;

/**
 * Class UserProducer
 * @package Sam\Import\Csv\Bidder\Internal\Process\Internal
 * @internal
 */
class UserProducer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use SettingsManagerAwareTrait;
    use SignupVerificationPreparerCreateTrait;
    use UserCustomerNoApplierCreateTrait;
    use UserMakerProducerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Created or update a bidder user.
     * The Input DTO and the config DTO should be filled from a CSV row.
     *
     * @param UserMakerInputDto $userInputDto
     * @param UserMakerConfigDto $userConfigDto
     * @param int $auctionAccountId
     * @return User
     */
    public function produce(UserMakerInputDto $userInputDto, UserMakerConfigDto $userConfigDto, int $auctionAccountId): User
    {
        $isNew = !$userInputDto->id;
        $producer = $this->createUserMakerProducer()->construct($userInputDto, $userConfigDto);
        $producer->produce();
        $user = $producer->getUser();
        if ($isNew) {
            log_debug('created user' . composeSuffix(['u' => $user->Id]));
            $this->notifyUserCreated($user, $auctionAccountId, $userConfigDto->editorUserId);
            $user = $this->createUserCustomerNoApplier()
                ->construct()
                ->apply($user, $userConfigDto->editorUserId);
        }
        return $user;
    }

    protected function notifyUserCreated(User $user, int $auctionAccountId, int $editorUserId): void
    {
        $shouldSendConfirmationLink = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::SEND_CONFIRMATION_LINK);
        $shouldVerifyEmail = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::VERIFY_EMAIL);

        if (
            $shouldSendConfirmationLink
            && $shouldVerifyEmail
        ) {
            $this->createSignupVerificationPreparer()
                ->construct($user->Id, $this->cfg()->get('core->portal->mainAccountId'))
                ->requireVerification($editorUserId);
            return;
        }

        $emailManager = Email_Template::new()->construct(
            $auctionAccountId,
            Constants\EmailKey::ACCOUNT_REG,
            $editorUserId,
            [$user]
        );
        $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);
    }
}
