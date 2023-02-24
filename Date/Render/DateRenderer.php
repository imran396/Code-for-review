<?php
/**
 * SAM-8666: Date renderer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Date\Render;

use DateInterval;
use DateTime;
use Exception;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Transform\Fraction\FractionAssemblerCreateTrait;
use Sam\Lang\TranslatorAwareTrait;

/**
 * Class DateRenderer
 * @package Sam\Date\Render
 */
class DateRenderer extends CustomizableClass
{
    use FractionAssemblerCreateTrait;
    use TranslatorAwareTrait;

    /** @var string[] */
    protected array $monthLangKeys = [
        1 => "MONTH_JANUARY",
        2 => "MONTH_FEBRUARY",
        3 => "MONTH_MARCH",
        4 => "MONTH_APRIL",
        5 => "MONTH_MAY",
        6 => "MONTH_JUNE",
        7 => "MONTH_JULY",
        8 => "MONTH_AUGUST",
        9 => "MONTH_SEPTEMBER",
        10 => "MONTH_OCTOBER",
        11 => "MONTH_NOVEMBER",
        12 => "MONTH_DECEMBER",
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $month
     * @return string
     */
    public function monthName(int $month): string
    {
        return Constants\Date::$monthNames[$month] ?? '';
    }

    /**
     * @param int $month
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    public function monthTranslated(int $month, ?int $accountId = null, ?int $languageId = null): string
    {
        $month = Cast::toInt($month, Constants\Date::$months);
        if (!$month) {
            return '';
        }
        return $this->getTranslator()->translate($this->monthLangKeys[$month], "auctions", $accountId, $languageId);
    }

    /**
     * Render time left by input seconds. E.g. "4d 5h 30m 45s".
     * Output is configurable by options. See unit test for details.
     * @param int $seconds
     * @param array|null $options null for default set of options:
     *  'extensions' => ['d ', 'h ', 'm ', 's'],
     *  'trimLeftZeros' => true,
     *  'trimRightZeros' => false,
     *  'trimCharList' => ' '
     * @return string
     * @throws Exception
     */
    public function renderTimeLeft(
        int $seconds,
        ?array $options = null
    ): string {
        $options = $options ?? [];
        $dateFrom = new DateTime();
        $seconds = max(0, $seconds); // negative number => 0s
        $dateTo = (clone $dateFrom)->add(new DateInterval('PT' . $seconds . 'S'));
        $interval = $dateTo->diff($dateFrom);
        $extensions = $options['extensions'] ?? ['d ', 'h ', 'm ', 's'];
        $output = $this->createFractionAssembler()
            ->enableTrimLeftZeros($options['trimLeftZeros'] ?? true)
            ->enableTrimRightZeros($options['trimRightZeros'] ?? false)
            ->setExtensions($extensions)
            ->setFractions([$interval->days, $interval->h, $interval->i, $interval->s])
            ->setTrimCharList($options['trimCharList'] ?? ' ')
            ->assemble();
        if ($output === '') {
            // We want to render zero seconds anyway
            $output = '0' . $extensions[count($extensions) - 1];
        }
        return $output;
    }
}
