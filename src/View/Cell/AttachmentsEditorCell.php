<?php
namespace Backend\View\Cell;

use Cake\ORM\TableRegistry;
use Cake\View\Cell;

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
            'multi' => true
        ], $params);

        $remoteSrc = $params['remoteSrc'];
        unset($params['remoteSrc']);

        $multi = $params['multi'];
        unset($params['multi']);

        $Attachments = TableRegistry::get('Attachment.Attachments');
        $query = $Attachments->find()->where($params);

        if ($multi) {
            $attachments = $query->all();
        } else {
            $attachments = $query->first();
        }
        $this->set('attachments', $attachments);

        $attachment = $Attachments->newEntity($params);
        if ($this->request->is('post') || $this->request->is('put')) {
            $Attachments->patchEntity($attachment, $this->request->data);
        }
        $this->set('attachment', $attachment);

        $this->set('files', $files);
        $this->set('multi', $multi);
    }
}
