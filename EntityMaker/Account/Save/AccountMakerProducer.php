<?php
/**
 * Class for producing of Account entity
 *
 * SAM-8855: Account entity-maker module structural adjustments for v3-5
 * SAM-3942: Account entity maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 2, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Account\Save;

use Account;
use Exception;
use RuntimeException;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Account\Load\Exception\CouldNotFindAccount;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Date\CurrentDateTrait;
use Sam\EntityMaker\Account\Dto\AccountMakerConfigDto;
use Sam\EntityMaker\Account\Dto\AccountMakerDtoHelperAwareTrait;
use Sam\EntityMaker\Account\Dto\AccountMakerInputDto;
use Sam\EntityMaker\Base\Save\BaseMakerProducer;
use Sam\EntityMaker\Base\Save\Internal\EntitySync\EntitySyncSavingIntegratorCreateTrait;
use Sam\Storage\WriteRepository\Entity\Account\AccountWriteRepositoryAwareTrait;

/**
 * @method AccountMakerInputDto getInputDto()
 * @method AccountMakerConfigDto getConfigDto()
 */
class AccountMakerProducer extends BaseMakerProducer
{
    use AccountLoaderAwareTrait;
    use AccountMakerDtoHelperAwareTrait;
    use AccountWriteRepositoryAwareTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;
    use EntityFactoryCreateTrait;
    use EntitySyncSavingIntegratorCreateTrait;

    protected ?Account $resultAccount = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AccountMakerInputDto $inputDto
     * @param AccountMakerConfigDto $configDto
     * @return static
     */
    public function construct(
        AccountMakerInputDto $inputDto,
        AccountMakerConfigDto $configDto
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        $this->getAccountMakerDtoHelper()->construct($configDto->mode);
        return $this;
    }

    /**
     * Produce the account entity
     * @return static
     */
    public function produce(): static
    {
        $this->assertInputDto();
        $configDto = $this->getConfigDto();
        /** @var AccountMakerInputDto $inputDto */
        $inputDto = $this->getAccountMakerDtoHelper()->prepareValues($this->getInputDto(), $configDto);
        $this->setInputDto($inputDto);
        $this->assignValues();
        $this->atomicSave();
        return $this;
    }

    /**
     * Atomic persist data.
     * @throws Exception
     */
    protected function atomicSave(): void
    {
        $this->transactionBegin();
        try {
            $this->save();
        } catch (Exception $e) {
            log_errorBackTrace("Rollback transaction, because account save failed.");
            $this->transactionRollback();
            throw $e;
        }
        $this->transactionCommit();
    }

    /**
     * Persist data.
     * Note: When new Account entity is created, then we call its observer, but AuctionParameters is not created yet,
     * thus we shouldn't call any logic that depends on AuctionParameters from observer handlers.
     */
    protected function save(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $this->getAccountWriteRepository()->saveWithModifier($this->resultAccount(), $configDto->editorUserId);
        $isNew = !$inputDto->id;
        if ($isNew) {
            $this->doPostCreate();
        } else {
            $this->doPostUpdate();
        }
    }

    /**
     * Get Account
     * @return Account
     */
    public function resultAccount(): Account
    {
        if (!$this->resultAccount) {
            throw new RuntimeException("Result Account entity is undefined");
        }
        return $this->resultAccount;
    }

    /**
     * Assign account values from Dto object
     */
    public function assignValues(): void
    {
        $inputDto = $this->getInputDto();

        $account = $this->loadAccountOrCreate();
        $this->setIfAssign($account, 'address');
        $this->setIfAssign($account, 'address2');
        $this->setIfAssign($account, 'auctionIncAllowed', self::STRATEGY_BOOL);
        $this->setIfAssign($account, 'city');
        $this->setIfAssign($account, 'companyName');
        $this->setIfAssign($account, 'county');
        $this->setIfAssign($account, 'email');
        $this->setIfAssign($account, 'hybridAuctionEnabled', self::STRATEGY_BOOL);
        $this->setIfAssign($account, 'buyNowSelectQuantityEnabled', self::STRATEGY_BOOL);
        $this->setIfAssign($account, 'name');
        $this->setIfAssign($account, 'notes');
        $this->setIfAssign($account, 'phone');
        $this->setIfAssign($account, 'publicSupportContactName');
        $this->setIfAssign($account, 'showAll', self::STRATEGY_BOOL);
        $this->setIfAssign($account, 'siteUrl');
        $this->setIfAssign($account, 'urlDomain');
        $this->setIfAssign($account, 'zip');
        if (isset($inputDto->country)) {
            $account->Country = AddressRenderer::new()->normalizeCountry($inputDto->country);
        }
        if (isset($inputDto->stateProvince)) {
            $account->StateProvince = AddressRenderer::new()->normalizeState($account->Country, $inputDto->stateProvince);
        }

        $this->resultAccount = $account;
    }

    /**
     * Run necessary actions after the account was created
     *
     */
    protected function doPostCreate(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $inputDto->id = $this->resultAccount()->Id;

        AccountMakerPostProducer::new()->create($this->resultAccount(), $configDto->editorUserId);
        $this->createEntitySyncSavingIntegrator()->create($this);
    }

    /**
     * Run necessary actions after the account was updated
     */
    protected function doPostUpdate(): void
    {
        $configDto = $this->getConfigDto();
        AccountMakerPostProducer::new()->update($this->resultAccount()->Id, $configDto->editorUserId);
        $this->createEntitySyncSavingIntegrator()->update($this);
    }

    /**
     * Load or create account depending on the account id
     * @return Account
     */
    private function loadAccountOrCreate(): Account
    {
        $inputDto = $this->getInputDto();
        $accountId = (int)$inputDto->id;
        if ($accountId) {
            $account = $this->getAccountLoader()->load($accountId);
            if (!$account) {
                throw CouldNotFindAccount::withId($accountId);
            }
        } else {
            $account = $this->createEntityFactory()->account();
            $account->Active = true;
        }
        return $account;
    }
}
