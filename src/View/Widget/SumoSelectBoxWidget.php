<?php
namespace Backend\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\View;
use Cake\View\Widget\SelectBoxWidget;

class SumoSelectBoxWidget extends SelectBoxWidget
{
    /**
     * Constructor.
     *
     * @param \Cake\View\StringTemplate $templates Templates list.
     * @param View $view
     */
    public function __construct($templates, View $view)
    {
        parent::__construct($templates);

        $view->loadHelper('Backend.SumoSelect');
    }

    /**
     * Render sumo selectbox
     */
    public function render(array $data, ContextInterface $context)
    {
        // generate dom id
        if (!isset($data['id'])) {
            $data['id'] = uniqid('sumoselect');
        }

        $data['empty'] = false;

        $html = parent::render($data, $context);

        //@see https://github.com/HemantNegi/jquery.sumoselect
        $sumo = [
            'showTitle' => true, //(boolean) set to false to prevent title (tooltip) from appearing (deafult true)
            'up' => false, //(boolean) the direction in which to open the dropdown (default: false)
            'search' => true, // (boolean) To enable searching in sumoselect (default is false).
            'searchText' => __('Search ...'), // (string) placeholder for search input.
            'noMatch' => __('No matches for "{0}"'), // (string) placeholder to display if no itmes matches the search term
            'locale' => [__('OK'), __('Cancel'), __('Select All')], // (array) the text used in plugin
            'placeholder' => __('Select Here'), //  The palceholder text to be displayed in the rendered select widget
            'captionFormat' => __('{0} Selected'), // (string) Its the format in which you want to see the caption when more than csvDispCount items are selected.
            'captionFormatAllSelected' => __('{0} all selected!'), // (string) Format of caption text when all elements are selected.
            //'csvDispCount' => 3,
            //'floatWidth' => 400,
        ];
        if (isset($data['sumo'])) {
            $sumo = array_merge($sumo, (array)$data['sumo']);
        }

        $js = sprintf("<script>$(document).ready(function() { $('#%s').SumoSelect(%s); });</script>", $data['id'], json_encode($sumo));
        return $html . $js;
    }

//    /**
//     * Ignore the 'empty' value
//     */
//    protected function _emptyValue($empty)
//    {
//        return [];
//    }
}
