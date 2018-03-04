<?php
namespace Backend\Controller\Admin;

use Banana\Banana;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Form\Schema;
use Cake\Network\Exception\BadRequestException;
use Cake\Utility\Hash;
use Settings\Form\SettingsForm;
use Settings\Model\Table\SettingsTable;
use Settings\SettingsManager;

/**
 * Settings Controller
 *
 * @property SettingsTable $Settings
 */
class SettingsController extends AppController
{
    /**
     * @var string
     */
    public $modelClass = 'Settings.Settings';


    public $actions = [
        'index2' => 'Backend.Index',
        'edit' => 'Backend.Edit',
        'view' => 'Backend.View',
    ];

    /**
     * @var SettingsManager
     */
    public $sm;

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $manager = Banana::getInstance()->settingsManager();
        $this->eventManager()->dispatch(new Event('Settings.build', $manager));


        $this->sm = $manager;
    }

    /**
     * Load settings values from persistent storage
     */
    protected function _loadValues($scope)
    {
        $values = [];
        $settings = $this->Settings
            ->find()
            ->where(['Settings.scope' => $scope])
            ->all();

        foreach ($settings as $setting) {
            $values[$setting->key] = $setting->value;
        }

        return $values;
    }

    protected function _saveValues($scope, $compiled)
    {
        $settings = $this->Settings
            ->find()
            ->where(['Settings.scope' => $scope])
            ->all();

        $copy = $compiled;

        foreach($settings as $setting) {
            $key = $setting->key;
            if (isset($compiled[$key])) {
                $setting->set('value', $compiled[$key]);
                unset($compiled[$key]);
            } else {
                $setting->set('value', null);
            }

            if (!$this->Settings->save($setting)) {
                debug("Failed saving setting for key $key");
                return false;
            }
        }

        foreach ($compiled as $key => $val) {
            $setting = $this->Settings->newEntity(['key' => $key, 'value' => $val, 'scope' => $scope]);
            if (!$this->Settings->save($setting)) {
                debug("Failed adding setting for key $key");
                return false;
            }
        }

        Configure::write($copy);

        return $this->_dump($scope, $copy);
    }

    /**
     * @return int
     * @TODO Refactor using config engine
     */
    protected function _dump($scope, $compiled)
    {
        $path = SETTINGS . 'settings_' . $scope . '.php';
        $contents = '<?php' . "\n" . 'return ' . var_export($compiled, true) . ';';

        return file_put_contents($path, $contents);
    }

    public function manage($scope = null, $group = null)
    {
        $scope = ($scope) ?: BC_SITE_ID;
        $values = $this->_loadValues($scope);
        $this->sm->apply($values);

        if ($this->request->is('post')) {
            //debug(Hash::flatten($this->request->data()));
            $values = Hash::flatten($this->request->data());
            $this->sm->apply($values);
            $compiled = $this->sm->getCompiled();
            if (!$this->_saveValues($scope, $compiled)) {
                $this->Flash->error("Failed to update values");
            } else {
                $this->Flash->success("Saved!");
                //$this->redirect(['action' => 'manage', $scope]);
            }
        }

        $this->set('scope', $scope);
        $this->set('group', $group);
        $this->set('manager', $this->sm);
        $this->set('result', $this->sm->describe());
    }

    public function index()
    {
        $this->set('manager', $this->sm);
        $this->set('result', $this->sm->describe());
    }

    public function index2($scope = null)
    {
        $scope = ($scope) ?: BC_SITE_ID;
        $this->set('fields.whitelist', ['id', 'scope', 'key', 'value']);

        $this->eventManager()->on('Backend.Action.Index.getActions', function(Event $event) use ($scope) {
            $event->result[] =  [__d('backend','Edit'), ['action' => 'form', $scope]];
            $event->result[] =  [__d('backend','Dump'), ['action' => 'dump', $scope]];
        });
        $this->Action->execute();
    }

    /**
     * Index method
     *
     * @return void
     */
    public function form($scope = 'default')
    {

        $settingsForm = new SettingsForm(new SettingsManager($scope));

        if ($this->request->is(['put', 'post'])) {
            // apply
            $settingsForm->execute($this->request->data);

            // compile
            $compiled = $settingsForm->manager()->getCompiled();
            //Configure::write($compiled);

            // update
            if ($this->Settings->updateSettings($compiled, $scope)) {

                // dump
                $settingsForm->manager()->dump();

                $this->Flash->success('Settings updated');
                $this->redirect(['action' => 'index', $scope]);
            }
        }

        //$this->set('settings', $settings);
        $this->set('scope', $scope);
        $this->set('form', $settingsForm);
        $this->set('_serialize', ['settings']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $setting = $this->Settings->newEntity();
        if ($this->request->is('post')) {
            $setting = $this->Settings->patchEntity($setting, $this->request->data);
            if ($this->Settings->save($setting)) {
                $this->Flash->success(__d('backend', 'The {0} has been saved.', __d('backend', 'setting')));

                return $this->redirect(['action' => 'edit', $setting->id]);
            } else {
                $this->Flash->error(__d('backend', 'The {0} could not be saved. Please, try again.', __d('backend', 'setting')));
            }
        }
        $this->set(compact('setting'));
        $this->set('valueTypes', $this->Settings->listValueTypes());
        $this->set('_serialize', ['setting']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Setting id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $setting = $this->Settings->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $setting = $this->Settings->patchEntity($setting, $this->request->data);
            if ($this->Settings->save($setting)) {
                //$this->Settings->dump();
                $this->Flash->success(__d('backend', 'The {0} has been saved.', __d('backend', 'setting')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('backend', 'The {0} could not be saved. Please, try again.', __d('backend', 'setting')));
            }
        }
        $this->set(compact('setting'));
        $this->set('_serialize', ['setting']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Setting id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $setting = $this->Settings->get($id);
        if ($this->Settings->delete($setting)) {
            $this->Flash->success(__d('backend', 'The {0} has been deleted.', __d('backend', 'setting')));
        } else {
            $this->Flash->error(__d('backend', 'The {0} could not be deleted. Please, try again.', __d('backend', 'setting')));
        }

        return $this->redirect(['action' => 'index']);
    }
}
