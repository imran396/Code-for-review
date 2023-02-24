<?php

/**
 * Short cut to render an inline help tag
 * @param string $section inline help file
 * @param string $key the inline help key
 * @param bool $shouldDisplayOutput echo or return output
 * @return string rendered inline help
 */
function _h(string $section, string $key, bool $shouldDisplayOutput = true): string
{
    try {
        $inlineHelp = InlineHelp::getInstance();
        $inlineHelp->setSection($section);
        $inlineHelp->setKey($key);
        if ($shouldDisplayOutput) {
            echo $inlineHelp;
        } else {
            return $inlineHelp->__toString();
        }
    } catch (Exception $e) {
        log_warning($e->getMessage());
    }
    return '';
}

/**
 * Shortcut to render an inline help tag with already translated message
 * @param string $message
 * @param bool $shouldDisplayOutput echo or return output
 * @return string rendered inline help
 */
function _ht(string $message, bool $shouldDisplayOutput = true): string
{
    try {
        $inlineHelp = InlineHelp::getInstance();
        if ($shouldDisplayOutput) {
            echo $inlineHelp->getHelpHtml($message);
        } else {
            return $inlineHelp->getHelpHtml($message);
        }
    } catch (Exception $e) {
        log_warning($e->getMessage());
    }
    return '';
}

/**
 * Short cut to render an inline help label
 * @param string $label the label
 * @param string $section inline help file
 * @param string $key inline help key
 * @param bool $shouldDisplayOutput default true, echo or return output
 * @param string $htmlTag inline help html tag, default span
 * @return string
 */
function _hl(string $label, string $section, string $key, bool $shouldDisplayOutput = true, string $htmlTag = 'span'): string
{
    try {
        $inlineHelp = InlineHelpLabel::getInstance();
        $inlineHelp->setLabel($label);
        $inlineHelp->setSection($section);
        $inlineHelp->setKey($key);
        $inlineHelp->setHtmlTag($htmlTag);
        if ($shouldDisplayOutput) {
            echo $inlineHelp;
        } else {
            return $inlineHelp->__toString();
        }
    } catch (Exception $e) {
        log_warning($e->getMessage());
    }
    return '';
}

/**
 * Shortcut to render an inline help label with already translated message
 * @param string $label
 * @param string $message
 * @param bool $shouldDisplayOutput echo or return output
 * @param string $htmlTag inline help html tag, default span
 * @return string rendered inline help
 */
function _hlt(string $label, string $message, bool $shouldDisplayOutput = true, string $htmlTag = 'span'): string
{
    try {
        $inlineHelp = InlineHelpLabel::getInstance();
        $inlineHelp->setLabel($label);
        $inlineHelp->setHtmlTag($htmlTag);
        if ($shouldDisplayOutput) {
            echo $inlineHelp->getHelpHtml($message);
        } else {
            return $inlineHelp->getHelpHtml($message);
        }
    } catch (Exception $e) {
        log_warning($e->getMessage());
    }
    return '';
}

/**
 * @param string $label
 * @param string $section
 * @param string $key
 * @param string $htmlTag
 * @return null|string
 */
function renderInlineHelp(string $label, string $section, string $key, string $htmlTag = 'span'): ?string
{
    $output = null;
    try {
        $inlineHelp = InlineHelpLabel::getInstance();
        $inlineHelp->setLabel($label);
        $inlineHelp->setSection($section);
        $inlineHelp->setKey($key);
        $inlineHelp->setHtmlTag($htmlTag);
        $output = $inlineHelp->getHelpHtml();
    } catch (Exception) {
    }
    return $output;
}
