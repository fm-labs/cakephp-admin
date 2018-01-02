<?php

namespace Backend\View\Helper;

use Bootstrap\View\Helper\UiHelper;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\ORM\ResultSet;
use Cake\Utility\Hash;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;
use Cake\View\Helper\NumberHelper;
use Cake\View\Helper\TimeHelper;
use Cake\View\View;

/**
 * Class FormatHelper
 * @package Backend\View\Helper
 *
 * @property HtmlHelper $Html
 * @property NumberHelper $Number
 * @property TimeHelper $Time
 * @property UiHelper $Ui
 *
 * @TODO Remove hard dependency on Bootstrap plugin. Use mixin solution
 */
class FormatterHelper extends Helper
{
    /**
     * @var array
     */
    static protected $_formatters = [];

    /**
     * @param $formatterName
     * @param callable $formatter
     */
    public static function register($formatterName, callable $formatter)
    {
        self::$_formatters[$formatterName] = $formatter;
    }

    /**
     * @var array
     */
    public $helpers = ['Html', 'Number', 'Time', 'Bootstrap.Ui'];

    /**
     * @param View $View
     * @param array $config
     */
    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);

        // built-in formatters

        // escape
        self::register('escape', function ($val, $extra, $params) {
            return h($val);
        });

        // boolean
        self::register('boolean', function ($val, $extra, $params) {
            return $this->Ui->statusLabel($val);
        });

        // date
        self::register('date', function ($val, $extra, $params) {

            $format = DATE_W3C;
            if (isset($params['format'])) {
                $format = $params['format'];
            }

            return $this->Time->format($val, $format);
        });

        // link
        self::register('link', function ($val, $extra, $params) {

            $title = $url = $val;
            if (isset($params['url'])) {
                $url = $params['url'];
                unset($params['url']);
            }

            $url = $this->Html->Url->build($url, true);
            if (isset($params['title'])) {
                $title = $params['title'];
            }

            return $this->Html->link($title, $url, $params);
        });

        // number
        self::register('number', function ($val, $extra, $params) {
            return $this->Number->format($val, $params);
        });

        // currency
        self::register('currency', function ($val, $extra, $params) {
            $currency = (isset($params['currency']))
                ? $params['currency'] : null; // @TODO Use App-level default currency
            $currency = (!$currency && $extra && isset($params['currency_field']))
                ? Hash::get($extra, $params['currency_field']) : $currency;

            return $this->Number->currency($val, $currency, $params);
        });

        // email
        self::register('email', function ($val, $extra, $params) {
            return ($val) ? $this->Html->link($val, 'mailto:' . $val) : null;
        });

        // array
        self::register('array', function ($val, $extra, $params) {
            return '[Array]';
            //return '<pre>' . print_r($val, true) . '</pre>';
        });

        // NULL
        self::register('null', function ($val, $extra, $params) {
            return /*(Configure::read('debug')) ? 'null' :*/ '-';
        });

        // object
        self::register('object', function ($val, $extra, $params) {

            if ($val instanceof EntityInterface) {
                return '[entity:' . get_class($val) . ']';
            }

            if ($val instanceof ResultSet) {
                return '[resultset]';
            }

            if (method_exists($val, '__toString')) {
                return h((string)$val);
            }

            return '[object:' . get_class($val) . ']';

            //return '<pre>' . print_r($val, true) . '</pre>';
        });

        // html
        self::register('html', function ($val, $extra, $params) {
            //@todo sanitation
            return sprintf('<div class="html">' . $val . '</div>', $val);
        });

        self::register('related', function($val, $extra, $params) {

            if (!$val) return $val;

            $params = (is_string($params)) ? ['field' => $params] : $params;
            $params = array_merge(['field' => 'name'], $params);

            if ($val instanceof EntityInterface) {
                return $val->get($params['field']);
            } elseif (is_array($val)) {
                return (isset($val[$params['field']])) ? $val[$params['field']] : null;
            } elseif (is_object($val)) {
                return (isset($val->{$params['field']})) ? $val->{$params['field']} : null;
            }

            return (string) $val;
        });
    }

    /**
     * @return array
     */
    public function getFormatters()
    {
        return array_keys(self::$_formatters);
    }

    /**
     * @param $value
     * @param null $formatter
     * @param array $formatterArgs
     * @param array $extra
     * @return mixed
     */
    public function format($value, $formatter = null, $formatterArgs = [], $extra = [])
    {
        if ($formatter === false) {
            return $value;
        }

        // Fallback to default formatter based on values datatype
        $dataType = gettype($value);
        if ($formatter === null || $formatter === 'default') {
            $formatter = $dataType;
        }

        if (is_array($formatter)) {
            // ['formatter-name' => 'formatter-data']
            if (count($formatter) === 1) {
                $formatterArgs = current($formatter);
                $formatter = key($formatter);
            } elseif (count($formatter) === 2) {
                list($formatter, $formatterArgs) = $formatter;
            } else {
                debug("Unsupported formatter array format");

                return "[Array]";
            }
        }

        switch ($formatter) {
            case "null":
            case "NULL":
                $formatter = "null";
                break;

            case "integer":
            case "float":
            case "double":
            case "decimal":
                $formatter = 'number';
                break;

            case "unknown type":
            case "resource":
                return "[" . h($dataType) . "]";

            case "datetime":
            case "uuid":
            case "text":
            case "string":
                if (!isset(self::$_formatters[$formatter])) {
                    $formatter = 'escape';
                }
                break;
            default:
                break;
        }

        if (is_string($formatter)) {
            if (!isset(self::$_formatters[$formatter])) {
                //debug("Formatter $formatter not found for dataType $dataType");
                $formatter = null;
            } else {
                $formatter = self::$_formatters[$formatter];
            }
        }

        if (is_callable($formatter)) {
            return call_user_func_array($formatter, [$value, $extra, $formatterArgs, $this->_View]);
        }

        if ($formatter) {
            debug("Uncallable formatter");
            var_dump($formatter);
        }

        return $value;
    }
}
