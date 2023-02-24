<?php

namespace Sam\Api\Soap\Front\Entity\Account\Controller;

use InvalidArgumentException;
use Sam\Account\Delete\AccountDeleter;
use Sam\Api\Soap\Front\Entity\Base\Controller\SoapControllerBase;
use Sam\Core\Constants;
use Sam\Date\CurrentDateTrait;
use Sam\EntityMaker\Account\Dto\AccountMakerConfigDto;
use Sam\EntityMaker\Account\Dto\AccountMakerDtoFactory;
use Sam\EntityMaker\Account\Dto\AccountMakerInputDto;
use Sam\EntityMaker\Account\Save\AccountMakerProducer;
use Sam\EntityMaker\Account\Validate\AccountMakerValidator;
use Sam\EntityMaker\Base\Common\Mode;

/**
 * Class Account
 * @package Sam\Soap
 */
class AccountSoapController extends SoapControllerBase
{
    use CurrentDateTrait;

    protected array $defaultNamespaces = [
        'SAM account.id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete Account
     * @param string $key Key is the synchronization key, account.id or account_sync_key
     */
    public function delete(string $key): void
    {
        $accountNamespaceAdapter = new AccountNamespaceAdapter(
            (object)['Key' => $key],
            $this->namespace,
            $this->namespaceId,
            $this->editorUserAccountId
        );
        $account = $accountNamespaceAdapter->getEntity();
        $this->updateLastSyncIn($key, Constants\EntitySync::TYPE_ACCOUNT);
        AccountDeleter::new()->delete($account, $this->editorUserId);
    }

    /**
     * Create or update an Account.
     * Missing fields keep their content, empty fields will remove the field content
     *
     * @param \Sam\EntityMaker\Account\Dto\AccountMakerInputMeta $data
     * @return int
     * @throws InvalidArgumentException
     */
    public function save($data): int
    {
        $accountNamespaceAdapter = new AccountNamespaceAdapter(
            $data,
            $this->namespace,
            $this->namespaceId
        );
        $dataArr = (array)$accountNamespaceAdapter->toObject();
        $dataArr = $this->lowercaseSoapTags($dataArr);

        /**
         * @var AccountMakerInputDto $accountInputDto
         * @var AccountMakerConfigDto $accountConfigDto
         */
        [$accountInputDto, $accountConfigDto] = AccountMakerDtoFactory::new()
            ->createDtos(Mode::SOAP, $this->editorUserId, $this->editorUserAccountId, $this->editorUserAccountId);
        $accountInputDto->setArray($dataArr);

        $validator = AccountMakerValidator::new()->construct($accountInputDto, $accountConfigDto);
        if ($validator->validate()) {
            $producer = AccountMakerProducer::new()->construct($accountInputDto, $accountConfigDto);
            $producer->produce();
            return $producer->resultAccount()->Id;
        }

        $logData = ['acc' => $data->Id ?? 0, 'editor u' => $this->editorUserId];
        log_debug(implode("\n", $validator->getErrorMessages()) . composeSuffix($logData));
        throw new InvalidArgumentException(implode("\n", $validator->getErrorMessages()));
    }

    /**
     * @param array $data
     * @return array
     */
    public function lowercaseSoapTags(array $data): array
    {
        foreach ($data as $field => $value) {
            $data[lcfirst($field)] = $value;
            unset($data[$field]); // TODO: will remove absent keys with null values. Why they are there?
        }
        return $data;
    }
}
