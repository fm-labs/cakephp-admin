<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 4/18/16
 * Time: 10:11 PM
 */

namespace Backend\View\Helper;


use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;
use Cake\View\View;
use InvalidArgumentException;

/**
 * Class FormatHelper
 * @package Backend\View\Helper
 *
 * @property HtmlHelper $Html
 */
class FormatHelper extends Helper
{
    protected $_formatters = [];

    public $helpers = ['Html', 'Number', 'Backend.Ui'];

    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);

        // built-in formatters
        $this->_formatters['boolean'] = function($val, $data) {
            //return ($val === true) ? "Yes" : "No";
            return $this->Ui->statusLabel($val);
        };

        $this->_formatters['link'] = function($val) {
            return $this->Html->link($val);
        };

        $this->_formatters['number'] = function($val) {
            return $this->Number->format($val);
        };

        $this->_formatters['array'] = function($val) {
            return '<pre>' . print_r($val, true) . '</pre>';
        };

        $this->_formatters['object'] = function($val) {

            if (method_exists($val, '__toString')) {
                return h((string) $val);
            }

            return '<pre>' . print_r($val, true) . '</pre>';
        };
    }

    public function formatDataCell($cellName, $cellData, $formatter = null, $rowData = [])
    {
        if ($formatter === false) {
            return $cellData;
        }

        if (is_string($formatter)) {
            $formatter = (isset($this->_formatters[$formatter])) ? $this->_formatters[$formatter] : null;
        }

        if (is_callable($formatter)) {
            return call_user_func_array($formatter, [$cellData, $rowData, $cellName]);
        }


        // fallbacks
        $type = gettype($cellData);
        switch ($type) {
            case "integer":
            case "float":
            case "double":
                $type = 'number';
            case "boolean":
            case "array":
            case "object":
                return $this->formatDataCell($cellName, $cellData, $type, $rowData);
            case "unknown type":
            case "resource":
                return "[" . $type . "]";
            case "NULL":
            case "string":
            case "text":
            default:
                return h($cellData);
        }

    }
}