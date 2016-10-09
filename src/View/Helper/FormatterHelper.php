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

        $this->register('escape', function($val, $data) {
            return h($val);
        });
        $this->register('boolean', function($val, $data) {
            return $this->Ui->statusLabel($val);
        });
        $this->register('date', function($val, $data) {

            $format = DATE_W3C;
            if (isset($data['format'])) {
                $format = $data['format'];
            }

            return $this->Time->format($val, $format);
        });

        $this->register('link', function($val, $data) {

            $title = $url = $val;
            if (isset($data['url'])) {
                $url = $data['url'];
                unset($data['url']);
            }
            if (!isset($data['title'])) {
                $data['title'] = $title;
            }
            $url = $this->Html->Url->build($url, true);
            return $this->Html->link($val, $url, $data);
        });

        $this->register('number', function($val) {
            return $this->Number->format($val);
        });
        $this->register('array', function($val) {
            return '<pre>' . print_r($val, true) . '</pre>';
        });
        $this->register('object', function($val) {
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

    public function format( $value, $formatter = null, $data = [])
    {
        if ($formatter === false) {
            return $value;
        }

        // Fallback to default formatter based on values datatype
        if ($formatter === null) {
            $type = gettype($value);
            switch (strtolower($type)) {
                case "null":
                    return "NULL";

                case "integer":
                case "float":
                case "double":
                    $formatter = 'number';
                    break;

                case "text":
                case "string":
                    $formatter = 'escape';
                    break;

                case "boolean":
                case "array":
                case "object":
                    $formatter = $type;
                    break;

                case "unknown type":
                case "resource":
                    return "[" . h($type) . "]";

                default:
                    break;

            }
        }

        if (is_array($formatter)) {
            // ['formatter-name' => 'formatter-data']
            if (count($formatter) === 1) {
                $data = current($formatter);
                $formatter = key($formatter);
            }
        }

        if (is_string($formatter)) {
            if (!isset($this->_formatters[$formatter])) {
                debug("Formatter $formatter not found");
                $formatter = null;
            } else {
                $formatter = $this->_formatters[$formatter];
            }
        }


        if (is_callable($formatter)) {
            return call_user_func_array($formatter, [$value, $data]);
        }

        if ($formatter) {
            debug("Uncallable formatter");
            var_dump($formatter);
        }

        return h($value);
    }
}