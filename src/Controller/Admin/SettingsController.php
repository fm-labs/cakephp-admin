<?php
namespace Backend\Controller\Admin;

use Banana\Controller\Admin\AppController;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Core\Plugin;
use Cake\Filesystem\Folder;
use Cake\Network\Exception\BadRequestException;
use Settings\Model\Table\SettingsTable;
use Settings\Model\Entity\Setting;
use Settings\Configure\Engine\SettingsConfig;


/**
 * Settings Controller
 *
 * @property SettingsTable $Settings
 */
class SettingsController extends AppController
{
    public $modelClass = 'Settings.Settings';


    protected function _listCompiledSettingsFiles()
    {
        $folder = new Folder(SETTINGS);
        $files = $folder->findRecursive('.*\.schema\.json');
        return $files;
    }

    protected function _listCompiledSettings($assoc = true)
    {
        $files = $this->_listCompiledSettingsFiles();
        $compiled = [];

        // read schema json into array
        $sSchemaReader = function ($schemaFile) use ($assoc) {
            $content = file_get_contents($schemaFile);
            return json_decode($content, $assoc);
        };

        // walk all compiled settings files and read schema
        array_walk($files, function ($val) use (&$compiled, $sSchemaReader) {
            $key = basename($val, '.schema.json');
            $compiled[$key] = $sSchemaReader($val);
        });

        return $compiled;
    }

    protected function _getTypes()
    {
        return array_flip(Setting::typeMap());
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        if (!Plugin::loaded('Settings')) {
            throw new MissingPluginException(['plugin' => 'Settings']);
        }

        $this->set('settings', $this->Settings->find()->all());
        $this->set('_serialize', ['settings']);
    }


    public function import($ref = null, $scope = 'global')
    {

        if ($ref) {

            $settingsList = $this->Settings
                ->find('list', ['keyField' => 'name', 'valueField' => 'id'])
                ->where(['scope' => $scope])
                ->toArray();

            if ($ref == 'App') {
                $settingsPath = SETTINGS;
            } else {
                $settingsPath = Plugin::configPath($ref) . 'settings' . DS;
            }

            $schema = SettingsConfig::readSchema($settingsPath);
            $saved = $failed = 0;

            $settings = [];
            foreach ($schema as $key => $conf) {
                $setting = array_merge([
                    'type' => 'string',
                    'ref' => $ref,
                    'scope' => $scope,
                    'name' => $key,
                    'default' => null,
                ], $conf);

                $setting = $this->Settings->newEntity($setting);
                $setting->id = (isset($settingsList[$key])) ? $settingsList[$key] : null;

                if ($this->Settings->save($setting)) {
                    $saved++;
                } else {
                    $failed++;
                }

                $settings[] = $setting;
            }

            $this->Flash->set(sprintf("Settings saved: %s | failed: %s", $saved, $failed));
            $this->redirect(['action' => 'import']);
            return;
        }


        $paths = [];
        $paths['App'] = CONFIG;

        foreach(Plugin::loaded() as $plugin) {
            $paths[$plugin] = Plugin::path($plugin) . 'config' . DS;
        }


        $imports = [];
        foreach ($paths as $_ref => $path) {
            $file = $path . 'settings/schema.php';
            if (!file_exists($file)) {
                continue;
            }

            $settingsCount = $this->Settings->find()->where(['Settings.ref' => $_ref, 'Settings.scope' => $scope])->count();

            $imports[] = [
                'ref' => $_ref,
                'path' => $file,
                'imported' => $settingsCount,
                'scope' => $scope
            ];

            $this->set('imports', $imports);
            $this->set('_serialize', ['imports']);
        }



    }

    public function dump($scope = 'global')
    {
        if (!$scope) {
            throw new BadRequestException("Scope not defined");
        }
        $settings = $this->Settings->find()->where(['Settings.scope' => $scope, 'published' => true])->all()->toArray();

        $compiled = [];
        foreach ($settings as $setting) {
            $compiled[$setting->name] = $setting->value;
        }

        $filepath = SettingsConfig::buildSettingsFilePath(SETTINGS, $scope);

        //if (file_exists($filepath)) {
        //    debug("Setting file $filepath already exists");
        //    return;
        //}

        $written = $this->_dump($filepath, $compiled);

        if ($written) {
            $this->Flash->success(__('Dump settings with scope {0} to {1}: {2} bytes written', $scope, $filepath, $written));
        } else {
            $this->Flash->error(__('Dump settings with scope {0} to {1}: Failed', $scope, $filepath));
        }
        $this->redirect($this->referer(['action' => 'index']));
    }

    protected function _dump($filepath, array $data)
    {
        $contents = '<?php' . "\n" . 'return ' . var_export($data, true) . ';' . "\n";
        return file_put_contents($filepath, $contents);
    }

    /**
     * View method
     *
     * @param string|null $id Setting id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $setting = $this->Settings->get($id, [
            'contain' => []
        ]);
        $this->set('setting', $setting);
        $this->set('_serialize', ['setting']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $setting = $this->Settings->newEntity();
            $setting = $this->Settings->patchEntity($setting, $this->request->data);
            if ($this->Settings->save($setting)) {
                $this->Flash->success(__d('banana','The {0} has been saved.', __d('banana','setting')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('banana','The {0} could not be saved. Please, try again.', __d('banana','setting')));
            }
        } else {
            if ($this->request->query('key')) {
                $setting = $this->Settings->newEntity([
                    'ref' => $this->request->query('ref'),
                    'key' => $this->request->query('key'),
                    'type' => $this->request->query('type')
                ]);
            } else {
                $setting = $this->Settings->newEntity([
                    'ref' => $this->request->query('ref'),
                    'scope' => $this->request->query('scope'),
                    'name' => $this->request->query('name'),
                    'type' => $this->request->query('type')
                ]);
            }
        }
        $this->set(compact('setting'));
        $this->set('types', $this->_getTypes());
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
                $this->Flash->success(__d('banana','The {0} has been saved.', __d('banana','setting')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('banana','The {0} could not be saved. Please, try again.', __d('banana','setting')));
            }
        }
        $this->set(compact('setting'));
        $this->set('types', $this->_getTypes());
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
            $this->Flash->success(__d('banana','The {0} has been deleted.', __d('banana','setting')));
        } else {
            $this->Flash->error(__d('banana','The {0} could not be deleted. Please, try again.', __d('banana','setting')));
        }
        return $this->redirect(['action' => 'index']);
    }

}
