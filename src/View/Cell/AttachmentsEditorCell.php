<?php
namespace Backend\View\Cell;

use Cake\ORM\TableRegistry;
use Cake\View\Cell;
use Cake\I18n\I18n;

/**
 * AttachmentsEditor cell
 */
class AttachmentsEditorCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display(array $params = [], array $files = [])
    {
        $params = array_merge([
            'model' => null,
            'modelid' => null,
            'scope' => null,
            'remoteSrc' => null,
            'multi' => true,
            'locale' => false,
        ], $params);

        $Attachments = TableRegistry::get('Attachment.Attachments');

        if ($params['locale']) {
            $Attachments->enableI18n();
            $locale = ($params['locale'] === true) ? I18n::locale() : $params['locale'];
            $Attachments->locale($locale);
        }

        $query = $Attachments->find()->where([
            'Attachments.model' => $params['model'],
            'Attachments.modelid' => $params['modelid'],
            'Attachments.scope' => $params['scope'],
        ]);

        if ($params['multi']) {
            $attachments = $query->all();
        } else {
            $attachments = $query->first();
        }
        $this->set('attachments', $attachments);

        $attachment = $Attachments->newEntity($params);
        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data;
            if (isset($data['_locale'])) {
                $locale = $data['_locale'];
                $Attachments->locale($locale);
            }
            $attachment = $Attachments->patchEntity($attachment, $this->request->data);
        }
        $this->set('attachment', $attachment);
        $this->set('files', $files);
        $this->set('multi', $params['multi']);
        $this->set('locale', $params['locale']);
    }
}
