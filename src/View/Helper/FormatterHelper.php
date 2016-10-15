<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 4/18/16
 * Time: 10:11 PM
 */

namespace Backend\View\Helper;

//@TODO Remove hard dependency on Bootstrap plugin. Use mixin solution
use Bootstrap\View\Helper\UiHelper;
use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;
use Cake\View\Helper\NumberHelper;
use Cake\View\Helper\TimeHelper;
use Cake\View\View;
use InvalidArgumentException;

/**
 * Class FormatHelper
 * @package Backend\View\Helper
 *
 * @property HtmlHelper $Html
 * @property NumberHelper $Number
 * @property TimeHelper $Time
 * @property UiHelper $Ui
 */
class FormatterHelper extends Helper
{
    protected $_formatters = [];

    public $helpers = ['Html', 'Number', 'Time', 'Bootstrap.Ui'];

    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);

        // built-in formatters

        $this->register('escape', function($val, $extra, $params) {
            return h($val);
        });
        $this->register('boolean', function($val, $extra, $params) {
            return $this->Ui->statusLabel($val);
        });
        $this->register('date', function($val, $extra, $params) {

            $format = DATE_W3C;
            if (isset($params['format'])) {
                $format = $params['format'];
            }

            return $this->Time->format($val, $format);
        });

        $this->register('link', function($val, $extra, $params) {

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

        $this->register('number', function($val, $extra, $params) {
            return $this->Number->format($val);
        });

        $this->register('currency', function($val, $extra, $params) {
            $currency = (isset($params['currency'])) ? $params['currency'] : 'EUR';
            return $this->Number->currency($val, $currency);
        });

        $this->register('array', function($val, $extra, $params) {
            return '<pre>' . print_r($val, true) . '</pre>';
        });
        $this->register('object', function($val, $extra, $params) {
            if (method_exists($val, '__toString')) {
                return h((string) $val);
            }

            return '<pre>' . print_r($val, true) . '</pre>';
        });
    }

    public function register($formatterName, callable $formatter) {

        $this->_formatters[$formatterName] = $formatter;
    }

    public function getFormatters()
    {
        return array_keys($this->_formatters);
    }

    public function format( $value, $formatter = null, $formatterArgs = [], $extra = [])
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
                return "NULL";

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
                if (!isset($this->_formatters[$formatter])) {
                    $formatter = 'escape';
                }
                break;
            default:
                break;

        }


        if (is_string($formatter)) {
            if (!isset($this->_formatters[$formatter])) {
                debug("Formatter $formatter not found for dataType $dataType");
                $formatter = null;
            } else {
                $formatter = $this->_formatters[$formatter];
            }
        }


        if (is_callable($formatter)) {
            return call_user_func_array($formatter, [$value, $extra, $formatterArgs]);
        }

        if ($formatter) {
            debug("Uncallable formatter");
            var_dump($formatter);
        }

        return $value;
    }
}