<?php
/**
 * SAM-10435: Add csv quick upload form to locations page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Location;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\Location\Dto\LocationMakerConfigDto;
use Sam\EntityMaker\Location\Dto\LocationMakerDtoFactory;
use Sam\EntityMaker\Location\Dto\LocationMakerInputDto;
use Sam\Import\Csv\Base\ImportCsvColumnsHelperCreateTrait;
use Sam\Import\Csv\Read\CsvRow;
use Sam\Location\Load\LocationLoaderAwareTrait;

/**
 * Class LocationImportCsvDtoFactory
 * @package Sam\Location\Import
 */
class LocationImportCsvDtoFactory extends CustomizableClass
{
    use ImportCsvColumnsHelperCreateTrait;
    use LocationLoaderAwareTrait;

    protected int $editorUserid;
    protected int $accountId;
    protected int $serviceAccountId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $editorUserId
     * @param int $accountId
     * @param int $serviceAccountId
     * @return static
     */
    public function construct(int $editorUserId, int $accountId, int $serviceAccountId): static
    {
        $this->editorUserid = $editorUserId;
        $this->accountId = $accountId;
        $this->serviceAccountId = $serviceAccountId;
        return $this;
    }

    /**
     * Construct the UserMakerInput and UserMakerConfig DTO and fill with data from CSV row
     *
     * @param CsvRow $row
     * @return array
     */
    public function create(CsvRow $row): array
    {
        $name = $row->getCell(Constants\Csv\Location::NAME);
        $location = $this->getLocationLoader()->loadByName($name, $this->accountId);

        /**
         * @var LocationMakerInputDto $locationInputDto
         * @var LocationMakerConfigDto $locationConfigDto
         */
        [$locationInputDto, $locationConfigDto] = LocationMakerDtoFactory::new()
            ->createDtos(Mode::CSV, $this->editorUserid, $this->serviceAccountId, $this->accountId);

        $locationInputDto->id = $location->Id ?? null;
        $locationInputDto->address = $row->getCell(Constants\Csv\Location::ADDRESS);
        $locationInputDto->country = $row->getCell(Constants\Csv\Location::COUNTRY);
        $locationInputDto->city = $row->getCell(Constants\Csv\Location::CITY);
        $locationInputDto->county = $row->getCell(Constants\Csv\Location::COUNTY);
        $locationInputDto->logo = $row->getCell(Constants\Csv\Location::LOGO);
        $locationInputDto->name = $row->getCell(Constants\Csv\Location::NAME);
        $locationInputDto->state = $row->getCell(Constants\Csv\Location::STATE);
        $locationInputDto->zip = $row->getCell(Constants\Csv\Location::ZIP);

        return [$locationInputDto, $locationConfigDto];
    }
}
