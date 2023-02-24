<?php
/**
 * Mailing Lists Report Data Loader Create Trait
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

/**
 * Trait MailingListsReportDataLoaderCreateTrait
 */
trait MailingListsReportDataLoaderCreateTrait
{
    protected ?MailingListsReportDataLoader $mailingListsReportDataLoader = null;

    /**
     * @return MailingListsReportDataLoader
     */
    protected function createMailingListsReportDataLoader(): MailingListsReportDataLoader
    {
        $mailingListsReportDataLoader = $this->mailingListsReportDataLoader ?: MailingListsReportDataLoader::new();
        return $mailingListsReportDataLoader;
    }

    /**
     * @param MailingListsReportDataLoader $mailingListsReportDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setMailingListsReportDataLoader(MailingListsReportDataLoader $mailingListsReportDataLoader): static
    {
        $this->mailingListsReportDataLoader = $mailingListsReportDataLoader;
        return $this;
    }
}
