<?php
/**
 * Mailing Lists Report Data Loader
 *
 * SAM-6278: Refactor Mailing Lists Report page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 10, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\MailingListsReportForm\Load;

use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\MailingListTemplates\MailingListTemplatesReadRepository;
use Sam\Storage\ReadRepository\Entity\MailingListTemplates\MailingListTemplatesReadRepositoryCreateTrait;
use Sam\View\Admin\Form\MailingListsReportForm\Load\Dto\MailingListReportDto;
use Sam\View\Admin\Form\MailingListsReportForm\MailingListsReportConstants;

/**
 * Class MailingListsReportDataLoader
 */
class MailingListsReportDataLoader extends CustomizableClass
{
    use ApplicationAccessCheckerCreateTrait;
    use EditorUserAwareTrait;
    use FilterAccountAwareTrait;
    use LimitInfoAwareTrait;
    use MailingListTemplatesReadRepositoryCreateTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int - return value of Mailing List Templates count
     */
    public function count(): int
    {
        return $this->prepareMailingListTemplateRepository()->count();
    }

    /**
     * @return MailingListReportDto[] - return values for Mailing List Templates
     */
    public function loadDtos(): array
    {
        $mailingListTemplatesRepository = $this->prepareMailingListTemplateRepository();

        switch ($this->getSortColumn()) {
            case MailingListsReportConstants::ORD_NAME:
                $mailingListTemplatesRepository->orderByName($this->isAscendingOrder());
                break;
            case MailingListsReportConstants::ORD_PERIOD_START:
                $mailingListTemplatesRepository->orderByPeriodStart($this->isAscendingOrder());
                break;
            case MailingListsReportConstants::ORD_PERIOD_END:
                $mailingListTemplatesRepository->orderByPeriodEnd($this->isAscendingOrder());
                break;
            case MailingListsReportConstants::ORD_MONEY_SPENT_FROM:
                $mailingListTemplatesRepository->orderByMoneySpentFrom($this->isAscendingOrder());
                break;
            case MailingListsReportConstants::ORD_MONEY_SPENT_TO:
                $mailingListTemplatesRepository->orderByMoneySpentTo($this->isAscendingOrder());
                break;
            case MailingListsReportConstants::ORD_USER_TYPE:
                $mailingListTemplatesRepository->orderByUserType($this->isAscendingOrder());
                break;
            case MailingListsReportConstants::ORD_ID:
                $mailingListTemplatesRepository->orderById($this->isAscendingOrder());
                break;
            case MailingListsReportConstants::ORD_AUCTION_INFO:
                $mailingListTemplatesRepository
                    ->joinAuctionOrderByName($this->isAscendingOrder())
                    ->joinAuctionOrderBySaleNo($this->isAscendingOrder());
                break;
        }

        if ($this->getOffset()) {
            $mailingListTemplatesRepository->offset($this->getOffset());
        }

        if ($this->getLimit()) {
            $mailingListTemplatesRepository->limit($this->getLimit());
        }

        $rows = $mailingListTemplatesRepository->loadRows();
        $dtos = [];
        foreach ($rows as $row) {
            $dtos[] = MailingListReportDto::new()->loadDbRow($row);
        }
        return $dtos;
    }

    /**
     * @return MailingListTemplatesReadRepository
     */
    protected function prepareMailingListTemplateRepository(): MailingListTemplatesReadRepository
    {
        $repo = $this->createMailingListTemplatesReadRepository()
            ->joinAuction()
            ->filterDeleted([false, null])
            ->select(
                [
                    'mlt.id',
                    'mlt.money_spent_from',
                    'mlt.money_spent_to',
                    'mlt.user_type',
                    'mlt.period_start',
                    'mlt.period_end',
                    'mlt.name',
                    'a.name as auction_name',
                    'a.sale_num as sale_num',
                ]
            );
        if ($this->isAccountFiltering()) {
            if ($this->getFilterAccountId()) {
                $repo->filterAccountId($this->getFilterAccountId());
            }
        } else { //In case sam portal has been disabled again
            $repo->filterAccountId($this->getSystemAccountId());
        }

        return $repo;
    }

    /**
     * @return bool
     */
    public function isAccountFiltering(): bool
    {
        return $this->createApplicationAccessChecker()->isCrossDomainAdminOnMainAccountForMultipleTenant(
            $this->getEditorUserId(),
            $this->getSystemAccountId(),
            true
        );
    }
}
