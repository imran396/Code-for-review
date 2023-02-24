<?php

namespace Sam\Api\Soap\Front\Entity\User\Controller;

use InvalidArgumentException;
use Sam\Api\Soap\Front\Entity\Base\Controller\SoapControllerBase;
use Sam\Core\Constants;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\Base\Data\UserOutput;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerDtoFactory;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\EntityMaker\User\Save\UserMakerProducer;
use Sam\EntityMaker\User\Validate\UserMakerValidator;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\User\Delete\UserDeleterCreateTrait;

/**
 * Class User
 * @package Sam\Soap
 */
class UserSoapController extends SoapControllerBase
{
    use BlockCipherProviderCreateTrait;
    use UserDeleterCreateTrait;

    protected const ACCESS_DENIED = 'Access denied to delete user';

    private const NAMESPACE_ID = 'SAM user.id';
    private const NAMESPACE_CUSTOMER_NO = 'SAM user.customer_no';

    protected array $defaultNamespaces = [
        self::NAMESPACE_ID,
        self::NAMESPACE_CUSTOMER_NO
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete User
     * @param string $key Key is the synchronization key, user.id or user_sync_key
     */
    public function delete(string $key): void
    {
        $userNamespaceAdapter = new UserNamespaceAdapter(
            (object)['Key' => $key],
            $this->namespace,
            $this->namespaceId,
            $this->editorUserAccountId
        );
        $user = $userNamespaceAdapter->getEntity();
        $this->updateLastSyncIn($key, Constants\EntitySync::TYPE_USER);
        if (!$this->createUserDeleter()->canDelete($user, $this->editorUserId)) {
            log_error(self::ACCESS_DENIED . composeSuffix(['key' => $key]));
            throw new InvalidArgumentException(self::ACCESS_DENIED);
        }
        $this->createUserDeleter()->delete($user, $this->editorUserId);
    }

    /**
     * Save a User
     *
     * Missing fields keep their content,
     * Empty fields will remove the field content
     *
     * @param object $data
     * @return object
     */
    public function save(object $data): object
    {
        $userNamespaceAdapter = new UserNamespaceAdapter(
            $data,
            $this->namespace,
            $this->namespaceId,
            $this->editorUserAccountId
        );
        $data = $userNamespaceAdapter->toObject();

        $this->parseBuyersPremiums($data, 'BuyersPremiumsHybrid', 'buyersPremiumHybridDataRows');
        $this->parseBuyersPremiums($data, 'BuyersPremiumsLive', 'buyersPremiumLiveDataRows');
        $this->parseBuyersPremiums($data, 'BuyersPremiumsTimed', 'buyersPremiumTimedDataRows');
        $this->parseCommissions($data, 'ConsignorCommissions');
        $this->parseCommissions($data, 'SalesCommissions');
        $this->parseCustomFields($data, 'UserCustomFields');
        $this->parseRanges($data, 'ConsignorCommissionRanges');
        $this->parseRanges($data, 'ConsignorUnsoldFeeRanges');
        $this->parseRanges($data, 'ConsignorSoldFeeRanges');

        /**
         * @var UserMakerInputDto $userInputDto
         * @var UserMakerConfigDto $userConfigDto
         */
        [$userInputDto, $userConfigDto] = UserMakerDtoFactory::new()
            ->createDtos(Mode::SOAP, $this->editorUserId, null, $this->editorUserAccountId);
        $userInputDto->setArray((array)$data);

        $validator = UserMakerValidator::new()->construct($userInputDto, $userConfigDto);
        if ($validator->validate()) {
            $producer = UserMakerProducer::new()->construct($userInputDto, $userConfigDto);
            $producer->produce();

            $blockCipher = $this->createBlockCipherProvider()->construct();
            $userOutput = new UserOutput();
            $userOutput->UserId = $producer->getUser()->Id;
            $userOutput->CimId = $producer->cimCpi;
            $userOutput->VaultId = $blockCipher->decrypt($producer->getUserBilling()->NmiVaultId);

            return $userOutput;
        }

        $logData = ['u' => $data->Id ?? 0, 'editor u' => $this->editorUserId];
        $errorMessages = array_merge($validator->getMainErrorMessages(), $validator->getCustomFieldsErrors());
        log_debug(implode("\n", $errorMessages) . composeSuffix($logData));
        throw new InvalidArgumentException(implode("\n", $errorMessages));
    }
}
