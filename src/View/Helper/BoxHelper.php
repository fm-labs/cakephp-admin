<?php

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
            'box' => '<div class="box box-default with-border{{collapsedClass}}">{{header}}{{body}}{{footer}}</div>',
            'boxHeader' => '<div class="box-heading">{{icon}} {{title}}{{tools}}</div>',
            'boxTitle' => '<span class="box-title">{{title}}</span>',
            'boxIcon' => '<span class="box-icon"><i class="fa fa-{{icon}}"></i></span>',
            'boxTools' => '<div class="box-tools pull-right">{{tools}}</div>',
            'boxBody' => '<div class="box-body">{{contents}}</div>',
            'boxFooter' => '<div class="box-footer">{{contents}}</div>',
            'boxToolCollapseButton' => '<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>',
            'boxToolExpandButton' => '<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>'
        ],
    ];

    /**
     * @var array
     */
    protected $_defaultParams = [
        'id' => null,
        'autostart' => true,
        'collapsed' => false,
        'collapse' => false,
        'expand' => true,
        'icon' => null,
    ];

    /**
     * @var array
     */
    protected $_params = [];

    /**
     * @param null $title
     * @param array $params
     */
    public function create($title = null, $params = [])
    {
        $this->clean();

        if (is_array($title)) {
            $params = $title;
            $title = null;
        }

        $params['title'] = $title;

        $this->_params = array_merge($this->_defaultParams, $params);
        //$this->_id = ($this->_params['id']) ?: uniqid('box');

        if ($this->_params['autostart']) {
            $this->start();
        }
    }

    /**
     * Start heading block
     */
    public function heading()
    {
        $this->start('heading');
    }

    /**
     * Start body block
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

        return $this->templater()->format('box', [
            'collapsedClass' => ($this->_params['collapsed']) ? ' collapsed-box' : '',
            'header' => $this->_renderHeader(),
            'body' => $this->_renderBody(),
            'footer' => $this->_renderFooter()
        ]);
    }

    /**
     * @return null|string
     */
    protected function _renderHeader()
    {
        return $this->templater()->format('boxHeader', [
            'icon' => $this->_renderIcon(),
            'title' => $this->_renderTitle(),
            'tools' => $this->_renderTools(),
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
        $title = ($this->getContent('heading')) ?: $this->_params['title'];

        return $this->templater()->format('boxTitle', ['title' => $title]);
    }

    /**
     * @return null|string
     */
    protected function _renderBody()
    {
        return $this->templater()->format('boxBody', ['contents' => $this->getContent('body')]);
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

        return $this->templater()->format('boxFooter', ['contents' => $contents]);
    }

    /**
     * @return null|string
     */
    protected function _renderTools()
    {
        $tools = '';

        if ($this->_params['collapse']) {
            $collapseTempl = ($this->_params['collapsed']) ? 'boxToolExpandButton' : 'boxToolCollapseButton';
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
