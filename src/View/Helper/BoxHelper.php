<?php
declare(strict_types=1);

namespace Backend\View\Helper;

use Bootstrap\View\Helper\ContentBlockHelperTrait;
use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * Class BoxHelper
 *
 * @package Backend\View\Helper
 */
class BoxHelper extends Helper
{
    use ContentBlockHelperTrait;
    use StringTemplateTrait;

    /**
     * @var array
     */
    public $helpers = ['Html'];

    /**
     * Default config for this class
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
            'box' => '<div class="box {{class}}">{{header}}{{body}}{{footer}}</div>',
            'boxHeader' => '<div class="box-header {{class}}">{{icon}} {{title}}{{tools}}</div>',
            'boxTitle' => '<span class="box-title">{{title}}</span>',
            'boxIcon' => '<span class="box-icon"><i class="fa fa-{{icon}}"></i></span>',
            'boxTools' => '<div class="box-tools pull-right">{{tools}}</div>',
            'boxBody' => '<div class="box-body {{class}}">{{contents}}</div>',
            'boxFooter' => '<div class="box-footer {{class}}">{{contents}}</div>',
            'boxToolCollapseButton' => '<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>',
            'boxToolExpandButton' => '<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>',
        ],
    ];

    /**
     * Default parameters
     *
     * @var array
     */
    protected $_defaultParams = [
        'id' => null,
        'autostart' => true,
        'collapsed' => false,
        'collapse' => false,
        //'expand' => true,
        'icon' => null,
        'class' => 'box-solid',
        'headerClass' => 'with-border',
        'bodyClass' => '',
        'footerClass' => '',
        'collapsedClass' => 'collapsed-box',
    ];

    /**
     * Global parameters.
     * Can be set once and will apply to all newly created boxes
     *
     * @var array
     */
    protected $_globalParams = [];

    /**
     * @var array
     */
    protected $_params = [];

    /**
     * Set the default params for each box created
     * after using this method.
     * Pass an empty array to reset the defaults
     *
     * @param array $params Map of default params
     * @return $this
     */
    public function setDefaults(array $params = [])
    {
        $this->_globalParams = $params;

        return $this;
    }

    /**
     * @param null $title Box heading title
     * @param array $params Box params
     * @return void
     */
    public function create($title = null, $params = [])
    {
        $this->clean();

        if (is_array($title)) {
            $params = $title;
            $title = null;
        }

        $params['title'] = $title;

        $this->_params = array_merge($this->_defaultParams, $this->_globalParams, $params);
        //$this->_id = ($this->_params['id']) ?: uniqid('box');

        if ($this->_params['autostart']) {
            $this->start();
        }
    }

    /**
     * Start heading block
     *
     * @return void
     */
    public function heading()
    {
        $this->start('heading');
    }

    /**
     * Start tools block
     *
     * @return void
     */
    public function tools()
    {
        $this->start('tools');
    }

    /**
     * Start body block
     *
     * @param null|string $bodyStr Optional content to put in body after starting the block
     * @return void
     */
    public function body($bodyStr = null)
    {
        $this->start('body');

        if ($bodyStr) {
            echo $bodyStr;
        }
    }

    /**
     * Start footer block
     *
     * @param null|string $footerStr Optional content to put in footer after starting the block
     * @return void
     */
    public function footer($footerStr = null)
    {
        $this->start('footer');

        if ($footerStr) {
            echo $footerStr;
        }
    }

    /**
     * @return null|string
     */
    public function render()
    {
        $this->end();

        $class = $this->_params['class'];
        $class = $this->_params['collapsed'] ? $class . ' ' . $this->_params['collapsedClass'] : $class;

        return $this->templater()->format('box', [
            'class' => trim($class),
            'header' => $this->_renderHeader(),
            'body' => $this->_renderBody(),
            'footer' => $this->_renderFooter(),
        ]);
    }

    /**
     * @return null|string
     */
    protected function _renderHeader()
    {
        if (!$this->_params['title']) {
            return null;
        }

        return $this->templater()->format('boxHeader', [
            'icon' => $this->_renderIcon(),
            'title' => $this->_renderTitle(),
            'tools' => $this->_renderTools(),
            'class' => $this->_params['headerClass'],
        ]);
    }

    /**
     * @return null|string
     */
    protected function _renderIcon()
    {
        if (!$this->_params['icon']) {
            return "";
        }

        return $this->templater()->format('boxIcon', [
            'icon' => $this->_params['icon'],
        ]);
    }

    /**
     * @return null|string
     */
    protected function _renderTitle()
    {
        $title = $this->getContent('heading') ?: $this->_params['title'];

        return $this->templater()->format('boxTitle', ['title' => $title]);
    }

    /**
     * @return null|string
     */
    protected function _renderBody()
    {
        return $this->templater()->format('boxBody', [
            'contents' => $this->getContent('body'),
            'class' => $this->_params['bodyClass'],
        ]);
    }

    /**
     * @return null|string
     */
    protected function _renderFooter()
    {
        $contents = $this->getContent('footer');
        if (!$contents) {
            return "";
        }

        return $this->templater()->format('boxFooter', [
            'contents' => $contents,
            'class' => $this->_params['footerClass'],
        ]);
    }

    /**
     * @return null|string
     */
    protected function _renderTools()
    {
        $tools = $this->getContent('tools');

        if ($this->_params['collapse']) {
            $collapseTempl = $this->_params['collapsed'] ? 'boxToolExpandButton' : 'boxToolCollapseButton';
            $tools .= $this->templater()->format($collapseTempl, []);
        }

        return $this->templater()->format('boxTools', ['tools' => $tools]);
    }

/**
 * <div class="box box-default">
    <div class="box-header with-border">
    <h3 class="box-title">Collapsable</h3>
    <div class="box-tools pull-right">
    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    <div class="box-body">
    The body of the box
    </div><!-- /.box-body -->
    </div><!-- /.box -->
 */
}
