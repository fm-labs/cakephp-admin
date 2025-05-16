<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Cake\Core\Exception\MissingPluginException;
use Cake\Core\Plugin;

/**
 * Class SettingsController
 *
 * @package Admin\Controller\Admin
 * @property \Settings\Model\Table\SettingsTable $Settings
 * @deprecated
 */
class SettingsController extends AppController
{
    public ?string $defaultTable = 'Settings.Settings';

    /**
     * @throws \Exception
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        if (!Plugin::isLoaded('Settings')) {
            throw new MissingPluginException(['plugin' => 'Settings']);
        }
    }

    /**
     * @return void
     */
    public function index(): void
    {
        /*
        $schema = new SettingsSchema();
        $schema->load('Admin.settings');

        $form = new SettingsForm($schema);
        */
        $form = null;

        if ($this->getRequest()->is(['post'])) {
            if ($this->Settings->addAll($this->getRequest()->getData())) {
                $this->Flash->success('Settings updated');
            } else {
                $this->Flash->error('Failed to update settings');
            }
        }
    }
}
