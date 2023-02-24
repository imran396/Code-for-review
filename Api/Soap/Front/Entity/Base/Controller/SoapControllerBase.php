<?php

namespace Sam\Api\Soap\Front\Entity\Base\Controller;

use InvalidArgumentException;
use RuntimeException;
use Sam\Api\Soap\Front\Auth\SoapAuthenticator;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\EntityMaker\Base\Data\Range;
use Sam\EntitySync\Save\EntitySyncUpdaterCreateTrait;
use Sam\SyncNamespace\Load\SyncNamespaceLoaderAwareTrait;

/**
 * Abstract base class.
 * Implements general authentication mechanism and setting the sync_namespace.
 */
abstract class SoapControllerBase extends CustomizableClass
{
    use BaseCustomFieldHelperAwareTrait;
    use EntitySyncUpdaterCreateTrait;
    use SyncNamespaceLoaderAwareTrait;

    /**
     * Account of authorized user is used in different roles:
     * It plays the system account role, because we allow access to soap via main domain end-point only, but any portal user can call it.
     * It plays the service account role, because we allow to access entities by this account only, i.e. entities of account of authorized admin. Cross-domain admin doesn't have access to other accounts.
     * And it plays the role of editor user's account according its sense.
     * @var int|null
     */
    protected ?int $editorUserAccountId = null;
    /** @var int|null */
    protected ?int $editorUserId = null;
    /** @var string[] */
    protected array $defaultNamespaces = [];
    /** @var string|null */
    protected ?string $namespace = null;
    /** @var int|null */
    protected ?int $namespaceId = null;

    /**
     * Init
     * @param SoapAuthenticator $auth
     * @param string $namespace
     * @return static
     */
    public function init(SoapAuthenticator $auth, string $namespace): static
    {
        $user = $auth->getUser();
        if (!$user) {
            log_error("Available user not found, when init SOAP Api class " . composeSuffix(['u' => $auth->getUserId()]));
            return $this;
        }
        $this->setAccountId($user->AccountId);
        $this->setAuthenticatedUserId($user->Id);
        $this->setNamespace($namespace);
        return $this;
    }

    /**
     * Parse buyersPremiums from buyersPremiums, premium tags
     * @param object $data
     * @param string $field
     * @param string $inputDtoField
     */
    protected function parseBuyersPremiums(
        object $data,
        string $field = 'BuyersPremiums',
        string $inputDtoField = 'buyersPremiumDataRows'
    ): void {
        if (isset($data->$field)) {
            $premiums = $data->$field->Premium ?? [];
            if (is_object($premiums)) {
                $premiums = [$premiums];
            }
            $buyersPremiums = [];
            foreach ($premiums as $premium) {
                $buyersPremiums[] = [$premium->Amount, $premium->Fixed, $premium->Percent, $premium->Mode];
            }
            $data->$inputDtoField = $buyersPremiums;
        }
    }

    protected function parseRanges(object $data, string $field): void
    {
        if (isset($data->$field)) {
            $ranges = $data->$field->Range ?? [];
            if (is_object($ranges)) {
                $ranges = [$ranges];
            }
            $result = [];
            foreach ($ranges as $range) {
                $range = (array)$range;
                $keys = array_map('lcfirst', array_keys($range));
                $result[] = Range::fromArray(array_combine($keys, $range));
            }
            $data->$field = $result;
        }
    }

    /**
     * Parse Commissions from ConsignorCommissions, SalesCommissions tags
     * @param object $data
     * @param string $field
     */
    protected function parseCommissions(object $data, string $field): void
    {
        $internalTag = [
            'ConsignorCommissions' => 'ConsignorCommission',
            'SalesCommissions' => 'SalesCommission',
        ];

        if (isset($data->$field)) {
            $commissions = $data->$field->{$internalTag[$field]} ?? [];
            if (is_object($commissions)) {
                $commissions = [$commissions];
            }
            $result = [];
            foreach ($commissions as $premium) {
                $result[] = [$premium->Amount, $premium->Percent];
            }
            $data->$field = $result;
        }
    }

    /**
     * Parse CustomFields
     * @param object $data
     * @param string $customFieldsType AuctionCustomFields|LotCustomFields|UserCustomFields
     */
    protected function parseCustomFields(object $data, string $customFieldsType): void
    {
        if (isset($data->{$customFieldsType})) {
            $fields = ($data->{$customFieldsType}->Field ?? []);
            $fields = is_array($fields) ? $fields : [$fields];
            foreach ($fields as $field) {
                $soapTag = $this->getBaseCustomFieldHelper()->makeSoapTagByName($field->Name);
                $data->{$soapTag} = $field->Value;
            }
            unset($data->{$customFieldsType});
        }
    }

    /**
     * Parse increments from increments, increment tags
     * @param object&\stdClass $data
     */
    protected function parseIncrements(object $data): void
    {
        if (
            isset($data->Increment)
            && !isset($data->Increments)
        ) {
            $data->Increments->Increment = (object)['Start' => 0, 'Amount' => $data->Increment];
            unset($data->Increment);
        }

        if (isset($data->Increments)) {
            $increments = $data->Increments->Increment ?? [];
            if (is_object($increments)) {
                $increments = [$increments];
            }
            $resultIncrement = [];
            foreach ($increments as $increment) {
                $resultIncrement[] = [$increment->Start, $increment->Amount];
            }
            $data->Increments = $resultIncrement;
        }
    }

    /**
     * Parse taxStates from TaxStates, state tags
     * @param $data
     */
    protected function parseTaxStates($data): void
    {
        if (isset($data->TaxStates->State)) {
            $data->TaxStates = (array)$data->TaxStates->State;
        }
    }

    protected function updateLastSyncIn($key, $type): void
    {
        if (!$this->namespaceId) {
            return;
        }

        $this->getEntitySyncUpdater()->updateLastSyncIn(
            $type,
            $key,
            $this->namespaceId,
            $this->editorUserAccountId,
            $this->editorUserId
        );
    }

    /**
     * Set Account.id
     *
     * @param int $accountId
     * @throws InvalidArgumentException
     */
    private function setAccountId(int $accountId): void
    {
        $this->editorUserAccountId = Cast::toInt($accountId, Constants\Type::F_INT_POSITIVE);
    }

    /**
     * Default function to set AuthenticatedUserId
     *
     * @param int $authenticatedId
     */
    private function setAuthenticatedUserId(int $authenticatedId): void
    {
        $this->editorUserId = $authenticatedId;
    }

    /**
     * Sets Namespace based on return data from soap service or from custom namespaces
     *
     * @param string $namespace One of entity default namespaces or entry in SyncNamespace
     * @return bool True if successful
     * @throws RuntimeException If no result found
     */
    private function setNamespace(string $namespace): bool
    {
        $namespace = trim($namespace);
        if (in_array($namespace, $this->defaultNamespaces, true)) {
            $this->namespace = $namespace;
            $this->namespaceId = null;
            return true;
        }

        // TODO: This could lead to ambiguous results, but active namespaces should be unique
        $namespaces = $this->getSyncNamespaceLoader()
            ->loadByName($namespace);
        if (!$namespaces) {
            throw new RuntimeException("Unknown Namespace " . $namespace);
        }

        $this->namespace = $namespaces[0]->Name;
        $this->namespaceId = $namespaces[0]->Id;
        return true;
    }
}
