<?php
/**
 * SAM-6928: Sales staff user assignment and filtering control adjustments at the "User Edit" and the "Sales Report" pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @since           01-31, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\AddedBy\Autocomplete;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;
use Sam\User\AddedBy\Common\AccountRestriction\SalesStaffFilteringAccountDetector;
use Sam\User\AddedBy\Common\AccountRestriction\SalesStaffFilteringAccountDetectorCreateTrait;
use Sam\User\AddedBy\Autocomplete\Internal\Load\UserAddedByDataLoaderCreateTrait;
use Sam\User\AddedBy\Autocomplete\Internal\Load\UserAddedByDto;

/**
 * Class UserAddedByAutocompleteDataProvider
 * @package Sam\User\Load\Autocomplete
 */
class UserEditAddedByAutocompleteListBuilder extends CustomizableClass
{
    use AdminPrivilegeCheckerAwareTrait;
    use OptionalsTrait;
    use SalesStaffFilteringAccountDetectorCreateTrait;
    use UserAddedByDataLoaderCreateTrait;

    // --- Incoming values ---

    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_LABEL_TPL = 'labelTpl'; // string
    public const OP_TRIM_CHARS = 'trimChars'; // string

    // --- Internal values ---

    protected const LABEL_TPL_DEF = '%s - %s %s';
    protected const TRIM_CHARS_DEF = ' -';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param int $systemAccountId
     * @param int $editorUserId
     * @param int $targetUserId
     * @param string $searchTerm
     * @return array
     */
    public function buildOptions(
        int $systemAccountId,
        int $editorUserId,
        int $targetUserId,
        string $searchTerm
    ): array {
        if (!$targetUserId) {
            log_error('User id unknown when building "Added By" options');
            return [];
        }
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $filterAccountIds = $this->createSalesStaffFilteringAccountDetector()
            ->construct([SalesStaffFilteringAccountDetector::OP_IS_READ_ONLY_DB => $isReadOnlyDb])
            ->detect($targetUserId, $editorUserId, $systemAccountId);
        $dtos = $this->createUserAddedByDataLoader()
            ->load($searchTerm, $filterAccountIds, $isReadOnlyDb);
        if (!$dtos) {
            log_trace(
                '"Added By" options empty for required filtering conditions'
                . composeSuffix(['u' => $targetUserId, 'search' => $searchTerm])
            );
            return [];
        }

        $results = [];
        foreach ($dtos as $dto) {
            $resultData = [];
            $resultData['label'] = $this->makeLabel($dto);
            $resultData['value'] = $dto->id;
            $results[] = $resultData;
        }
        return $results;
    }

    /**
     * Build agent label info exclusively for the legitimate sales staff agent, otherwise result with empty string.
     * @param int|null $addedByUserId
     * @return string
     */
    public function buildLabelByLegitimateSalesStaffUserId(?int $addedByUserId): string
    {
        if (!$addedByUserId) {
            return '';
        }

        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $dto = $this->createUserAddedByDataLoader()
            ->loadByLegitimateSalesStaffUserId($addedByUserId, $isReadOnlyDb);
        if (!$dto) {
            return '';
        }

        return $this->makeLabel($dto);
    }

    /**
     * Build agent label info for any user.
     * @param int|null $userId
     * @return string
     */
    public function buildLabelByAnyUserId(?int $userId): string
    {
        if (!$userId) {
            return '';
        }

        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $dto = $this->createUserAddedByDataLoader()
            ->loadByAnyUserId($userId, $isReadOnlyDb);
        if (!$dto) {
            return '';
        }

        $label = $this->makeLabel($dto) . $this->makeNonLegitimateClarification($dto);
        return $label;
    }

    /**
     * @param UserAddedByDto $dto
     * @return string
     */
    protected function makeLabel(UserAddedByDto $dto): string
    {
        $labelTpl = (string)$this->fetchOptional(self::OP_LABEL_TPL);
        $trimChars = (string)$this->fetchOptional(self::OP_TRIM_CHARS);
        $label = sprintf($labelTpl, $dto->username, $dto->firstName, $dto->lastName);
        $label = trim($label, $trimChars);
        return $label;
    }

    /**
     * Non-legitimate clarification may be for user with revoked "Sales Staff" privilege,
     * because soft-deleted users are not viewable.
     * @param UserAddedByDto $dto
     * @return string
     */
    protected function makeNonLegitimateClarification(UserAddedByDto $dto): string
    {
        return $dto->isSalesStaff ? '' : ' (No "Sales Staff" privilege)';
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_READ_ONLY_DB] = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;
        $optionals[self::OP_LABEL_TPL] = $optionals[self::OP_LABEL_TPL] ?? self::LABEL_TPL_DEF;
        $optionals[self::OP_TRIM_CHARS] = $optionals[self::OP_TRIM_CHARS] ?? self::TRIM_CHARS_DEF;
        $this->setOptionals($optionals);
    }
}
