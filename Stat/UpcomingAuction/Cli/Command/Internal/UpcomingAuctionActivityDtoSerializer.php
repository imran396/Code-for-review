<?php
/**
 * SAM-7949: Predictive upcoming auction stats script
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Stat\UpcomingAuction\Cli\Command\Internal;

use Sam\Core\Service\CustomizableClass;
use Sam\Stat\UpcomingAuction\Load\Dto\UpcomingAuctionActivityDto;

/**
 * Class UpcomingAuctionActivityDtoSerializer
 * @package Sam\Stat\UpcomingAuction\Cli\Command\Internal
 * @internal
 */
class UpcomingAuctionActivityDtoSerializer extends CustomizableClass
{
    protected const FIELD_NAMES = [
        'utc',
        'lots',
        'bids_placed',
        'bidders_bidding',
        'bidders_registered',
        'domain',
        'auction_type',
        'auction_id',
        'published',
        'name',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param UpcomingAuctionActivityDto[] $activities
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escapeChar
     * @return string
     */
    public function serializeToCsv(array $activities, string $delimiter = ',', string $enclosure = '"', string $escapeChar = '\\'): string
    {
        $buffer = fopen('php://temp', 'r+');

        fputcsv($buffer, self::FIELD_NAMES);

        foreach ($activities as $activity) {
            fputcsv($buffer, $this->convertDtoToArray($activity));
        }
        rewind($buffer);
        $csv = stream_get_contents($buffer);
        fclose($buffer);
        return $csv;
    }

    /**
     * @param array $activities
     * @return string
     */
    public function serializeToJson(array $activities): string
    {
        $activitiesAsArray = array_map([$this, 'convertDtoToArray'], $activities);
        $json = json_encode($activitiesAsArray);
        return $json;
    }

    /**
     * @param UpcomingAuctionActivityDto $dto
     * @return array
     */
    protected function convertDtoToArray(UpcomingAuctionActivityDto $dto): array
    {
        $array = array_combine(
            self::FIELD_NAMES,
            [
                $dto->dateIso,
                $dto->lots,
                $dto->bidsPlaced,
                $dto->activeBidders,
                $dto->registeredBidders,
                $dto->accountDomain,
                $dto->auctionType,
                $dto->auctionId,
                (int)$dto->isPublished,
                $dto->auctionName
            ]
        );

        return $array;
    }
}
