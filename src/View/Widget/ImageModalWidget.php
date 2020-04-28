<?php
declare(strict_types=1);

namespace Admin\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\BasicWidget;

/**
 * Class ImageModalWidget
 * @package Admin\View\Widget
 * @deprecated Unused class
 */
class ImageModalWidget extends BasicWidget
{
    /**
     * {@inheritDoc}
     */
    public function render(array $data, ContextInterface $context): string
    {
        $data += [
            'val' => '',
            'name' => '',
            'escape' => true,
            'templateVars' => [],
        ];

        $domId = uniqid('imagemodal');
        $modalId = $domId . '-modal';
        $btnId = $domId . '-btn';

        $textarea = $this->_templates->format('textarea', [
            'name' => $data['name'],
            'value' => $data['escape'] ? h($data['val']) : $data['val'],
            'templateVars' => $data['templateVars'],
            'attrs' => $this->_templates->formatAttributes(
                $data,
                ['name', 'val']
            ),
        ]);

        $link = '<button id="' . $btnId . '">Select image</button>';

        $modalTemplate = <<<MODAL
<div class="ui modal" id="{{modalId}}">
    <i class="close icon"></i>
    <div class="header">
        Select image
    </div>
    <div class="content" style="overflow: scroll; max-height: 500px;">

    </div>
    <div class="actions">
        <div class="ui black deny button">
            Cancel
        </div>
        <div class="ui approve button">
            Ok
        </div>
    </div>
</div>
MODAL;

        $scriptTemplate = <<<SCRIPT
<script>
$(document).ready(function() {
    $('#{{btnId}}').click(function(e) {
        console.log("clicked");
        e.preventDefault();
        $('#{{modalId}}')
            .modal({

            })
            .modal('show');
    });
});
</script>
SCRIPT;
        $this->_templates->add([
            'imagemodalModal' => $modalTemplate,
            'imagemodalScript' => $scriptTemplate,
        ]);

        $modal = $this->_templates->format('imagemodalModal', [
            'modalId' => $modalId,
        ]);

        $script = $this->_templates->format('imagemodalScript', [
            'domId' => $domId,
            'btnId' => $btnId,
            'modalId' => $modalId,
        ]);

        return $textarea . $link . $modal . $script;
    }
}
