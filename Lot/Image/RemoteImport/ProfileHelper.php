<?php
/**
 * Helper method for managing profiles of Remote Image Import
 *
 * SAM-3915: Refactor logic for Remote image import
 *
 * @copyright         2018 Bidpath, Inc.
 * @author            Igors Kotlevskis
 * @package           com.swb.sam2
 * @version           SVN: $Id$
 * @since             18 Oct, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package           com.swb.sam2.api
 *
 */

namespace Sam\Lot\Image\RemoteImport;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Lot\Image\Load\ReportImageImportLoaderCreateTrait;
use Sam\Storage\ReadRepository\Entity\ReportImageImport\ReportImageImportReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\ReportImageImport\ReportImageImportWriteRepositoryAwareTrait;

/**
 * Class ProfileHelper
 * @package Sam\Lot\Image\RemoteImport
 */
class ProfileHelper extends CustomizableClass
{
    use LotCustomFieldLoaderCreateTrait;
    use ReportImageImportLoaderCreateTrait;
    use ReportImageImportReadRepositoryCreateTrait;
    use ReportImageImportWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Find profile id by name
     * @param string $name
     * @return int|null
     */
    public function findIdByName(string $name): ?int
    {
        $row = $this->createReportImageImportReadRepository()
            ->filterStatus(Constants\ReportImageImport::STATUS_ACTIVE)
            ->filterProfileName($name)
            ->select(['id'])
            ->loadRow();
        return Cast::toInt($row['id'] ?? null);
    }

    /**
     * Load list with profile name and id, used on report image import.
     * @param int $accountId
     * @param int $status
     * @return array
     */
    public function loadForList(int $accountId, int $status = Constants\ReportImageImport::STATUS_ACTIVE): array
    {
        $rows = $this->createReportImageImportReadRepository()
            ->filterAccountId($accountId)
            ->filterStatus($status)
            ->orderByProfileName()
            ->select(['id', 'profile_name'])
            ->loadRows();
        return $rows;
    }

    /**
     * Soft delete of report profile
     * @param int $profileId
     * @param int $editorUserId
     */
    public function delete(int $profileId, int $editorUserId): void
    {
        $reportProfile = $this->createReportImageImportLoader()->load($profileId, true);
        if (!$reportProfile) {
            log_error("Available ReportImageImport not found" . composeSuffix(['id' => $profileId]));
            return;
        }
        $reportProfile->Status = Constants\ReportImageImport::STATUS_DELETED;
        $this->getReportImageImportWriteRepository()->saveWithModifier($reportProfile, $editorUserId);
    }

    /**
     * @return array
     */
    public function detectIdentifiers(): array
    {
        $identifiers = [
            'Item #' => '\d+',
            'Prefix + Lot # + Extension' => '\d+[a-zA-Z]+(\d+)?([a-zA-Z]+)?',
        ];

        $lotCustomFields = $this->createLotCustomFieldLoader()->loadForLotByType(
            [Constants\CustomField::TYPE_INTEGER, Constants\CustomField::TYPE_TEXT]
        );

        if ($lotCustomFields) {
            foreach ($lotCustomFields as $lotCustomField) {
                if ($lotCustomField->Type === Constants\CustomField::TYPE_INTEGER) {
                    $identifiers[$lotCustomField->Name] = '\d+';
                } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_TEXT) {
                    $identifiers[$lotCustomField->Name] = '[^\/\\?%\*\:\|\"\<\>]+';
                }
            }
        }

        return $identifiers;
    }
}
