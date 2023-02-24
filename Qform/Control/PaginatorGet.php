<?php

namespace Sam\Qform\Control;

use QPaginatorGet;
use Sam\Core\Constants;

/**
 * Class PaginatorGet
 * @package Sam\Qform\Control
 */
class PaginatorGet extends QPaginatorGet
{
    /**
     * @return string
     */
    public function GetControlHtml(): string
    {
        $param = null;
        $selectPage = '';

        $get = $this->getParamFetcherForGet()->getParameters();
        unset($get['page'], $get[Constants\UrlParam::GA]);
        if (count($get)) {
            $param .= '&' . http_build_query($get);
        }

        $this->objPaginatedControl->DataBind();

        $style = $this->GetStyleAttributes();
        if ($style) {
            $style = sprintf(' style="%s"', $style);
        }

        $output = sprintf('<span id="%s" %s%s>', $this->controlId, $style, $this->GetAttributes(true, false));

        if ($this->intPageNumber <= 1) {
            $output .= sprintf('<span class="arrow">%s</span>', $this->strLabelForPrevious);
        } else {
            $this->strActionParameter = $this->intPageNumber - 1;
            $output .= sprintf(
                '<span class="arrow"><a href="?page=%s%s">%s</a></span>',
                $this->strActionParameter,
                $param,
                $this->strLabelForPrevious
            );
        }

        $output .= '<span class="break">|</span>';

        if ($this->PageCount <= $this->intIndexCount) {
            // We have less pages than total indexcount -- so let's go ahead
            // and just display all page indexes
            for ($index = 1; $index <= $this->PageCount; $index++) {
                if ($this->intPageNumber === $index) {
                    $output .= sprintf('<span class="selected">%s</span>', $index);
                } else {
                    $this->strActionParameter = $index;
                    $output .= sprintf(
                        '<span class="page"><a href="?page=%s%s" >%s</a></span>',
                        $index,
                        $param,
                        $index
                    );
                }
            }
        } else {
            // Figure Out Constants
            $minimumEndOfBunch = $this->intIndexCount - 2;
            $maximumStartOfBunch = $this->PageCount - $this->intIndexCount + 3;

            $leftOfBunchCount = floor(($this->intIndexCount - 5) / 2);
            $rightOfBunchCount = round(($this->intIndexCount - 5.0) / 2.0);

            $leftBunchTrigger = 4 + $leftOfBunchCount;
            $rightBunchTrigger = $maximumStartOfBunch + round(($this->intIndexCount - 8.0) / 2.0);

            if ($this->intPageNumber < $leftBunchTrigger) {
                $pageStart = 1;
                $startEllipsis = '';
            } else {
                $pageStart = min($maximumStartOfBunch, $this->intPageNumber - $leftOfBunchCount);

                $this->strActionParameter = 1;
                $startEllipsis = sprintf(
                    '<span class="page"><a href="?page=%s%s" >%s</a></span>',
                    1,
                    $param,
                    1
                );
                $startEllipsis .= '<span class="ellipsis">...</span>';
            }

            if ($this->intPageNumber > $rightBunchTrigger) {
                $pageEnd = $this->PageCount;
                $endEllipsis = '';
            } else {
                $pageEnd = max($minimumEndOfBunch, $this->intPageNumber + $rightOfBunchCount);
                $endEllipsis = '<span class="ellipsis">...</span>';

                $this->strActionParameter = $this->PageCount;
                $endEllipsis .= sprintf(
                    '<span class="page"><a href="?page=%s%s" >%s</a></span>',
                    $this->PageCount,
                    $param,
                    $this->PageCount
                );
            }

            $output .= $startEllipsis;
            for ($index = $pageStart; $index <= $pageEnd; $index++) {
                if ($this->intPageNumber === $index) {
                    $output .= sprintf('<span class="selected">%s</span>', $index);
                } else {
                    $this->strActionParameter = $index;
                    $output .= sprintf(
                        '<span class="page"><a href="?page=%s%s">%s</a></span>',
                        $index,
                        $param,
                        $index
                    );
                }
            }
            $output .= $endEllipsis;

            // dropdown for direct page access
            $selectPage = sprintf(
                '<div class="selectdrp"><select class="pageselector" onchange="document.location.href=\'?page=\'+this.options[this.selectedIndex].text+\'%s\';return false;">',
                $param
            );
            $pageCount = $this->PageCount;
            for ($index = 1; $index <= $pageCount; $index++) {
                $selected = $index === $this->intPageNumber ? ' selected="selected"' : '';
                $selectPage .= sprintf('<option%s>%s</option>', $selected, $index);
            }
            $selectPage .= '</select></div>';
            //$output .= $selectPage;
        }

        $output .= '<span class="break break-right">|</span>';

        if ($this->intPageNumber >= $this->PageCount) {
            $output .= sprintf('<span class="arrow">%s</span>', $this->strLabelForNext);
        } else {
            $this->strActionParameter = $this->intPageNumber + 1;
            $output .= sprintf(
                '<span class="arrow"><a href="?page=%s%s">%s</a></span>',
                $this->strActionParameter,
                $param,
                $this->strLabelForNext
            );
        }

        $output .= '</span>';

        //add the dropdown at the end
        $output .= $selectPage;

        return $output;
    }

    /**
     * @param string $name
     * @return array|float|int|mixed|null|string
     */
    public function __get($name)
    {
        switch ($name) {
            case 'IndexCount':
                return $this->intIndexCount;
            case 'LabelForNext':
                return $this->strLabelForNext;
            case 'LabelForPrevious':
                return $this->strLabelForPrevious;
            default:
                try {
                    return parent::__get($name);
                } catch (\QCallerException $e) {
                    $e->IncrementOffset();
                    throw $e;
                }
        }
    }

    /**
     * @param string $name
     * @param string $mixValue
     * @return mixed
     */
    public function __set($name, $mixValue)
    {
        switch ($name) {
            case 'IndexCount':
                try {
                    $this->intIndexCount = \QType::Cast($mixValue, \QType::Integer);
                    if ($this->intIndexCount < 7) {
                        throw new \QCallerException('Paginator must have an IndexCount >= 7');
                    }
                    return $this->intIndexCount;
                } catch (\QCallerException $e) {
                    $e->IncrementOffset();
                    throw $e;
                }

            case 'LabelForNext':
                try {
                    return ($this->strLabelForNext = \QType::Cast($mixValue, \QType::String));
                } catch (\QCallerException $e) {
                    $e->IncrementOffset();
                    throw $e;
                }

            case 'LabelForPrevious':
                try {
                    return ($this->strLabelForPrevious = \QType::Cast($mixValue, \QType::String));
                } catch (\QCallerException $e) {
                    $e->IncrementOffset();
                    throw $e;
                }

            default:
                try {
                    return parent::__set($name, $mixValue);
                } catch (\QCallerException $e) {
                    $e->IncrementOffset();
                    throw $e;
                }
        }
    }
}
