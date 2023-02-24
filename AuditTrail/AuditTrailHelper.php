<?php

namespace Sam\AuditTrail;

use DateTime;
use Sam\Application\ApplicationAwareTrait;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverSubject;

/**
 * Class AuditTrailHelper
 * @package Sam\AuditTrail
 */
class AuditTrailHelper extends CustomizableClass
{
    use ApplicationAwareTrait;
    use ServerRequestReaderAwareTrait;

    /**
     * Return an instance of AuditTrail_Helper
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Convert value to string to log in audit trail
     * @param int $length default 150 (for strings)
     */
    public function valToString(mixed $value, int $length = 150): string
    {
        switch (gettype($value)) {
            case 'NULL':
                $output = 'NULL';
                break;
            case 'boolean':
                $output = $value ? 1 : 0;
                break;
            case 'integer':
            case 'double':
                $output = $value;
                break;
            case 'string':
                $output = substr($value, 0, $length);
                $output = mb_check_encoding($output) ? preg_replace('/[\x00-\x1f]/', ' ', $output) : bin2hex($output);
                break;
            case 'object':
                $output = $value instanceof DateTime ? $value->format(Constants\Date::ISO) : $value::class;
                break;
            default:
                $output = gettype($value);
        }
        return $output;
    }

    /**
     * Try to determine the section using REQUEST_URI or $argv[0]
     */
    public function autoSection(): string
    {
        $section = '';
        global $argv;

        $isWeb = $this->getApplication()->ui()->isWeb();
        if ($isWeb) {
            $requestUri = $this->getServerRequestReader()->requestUri();
            $sections = preg_split('/\//', $requestUri, -1, PREG_SPLIT_NO_EMPTY);
            if (!$sections) {
                return '';
            }
            if ($sections[0] === 'admin') {
                array_shift($sections);
            }
            foreach ($sections as $segment) {
                if (is_numeric($segment)) {
                    break;
                }
                if (str_contains($segment, '?')) {
                    $tmpSegments = explode('?', $segment);
                    $segment = $tmpSegments[0];
                    if ($segment !== '') {
                        if ($section !== '') {
                            $section .= '/';
                        }
                        $section .= $segment;
                    }
                    break;
                }
                if ($section !== '') {
                    $section .= '/';
                }
                $section .= $segment;
            }
        } elseif (isset($argv[0])) {
            $section = basename($argv[0], '.php');
        } else {
            $section = 'n/a';
        }

        return $section;
    }

    public function makeEntityModificationMessage(EntityObserverSubject $subject): string
    {
        $changes = [];
        $entity = $subject->getEntity();
        foreach ($subject->getOldPropertyValues() as $k => $v) {
            $newValue = $this->valToString($entity->$k);
            $oldValue = $this->valToString($v);
            $changes[] = $k . ': ' . (($newValue !== $oldValue) ? $oldValue . '=>' : '') . $newValue;
        }
        return implode('; ', $changes);
    }
}
