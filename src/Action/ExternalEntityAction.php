<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Http\Response;
use Cake\Utility\Inflector;

class ExternalEntityAction extends BaseEntityAction
{
    protected ?string $_action = null;

    protected array $_attributes = [];

    protected ?string $label = 'External';

    protected array $scope = ['table'];

    protected string|array|null $_url = null;

    /**
     * @inheritDoc
     */
    public function __construct(string $action, array $options = [])
    {
        $options += ['url' => null, 'label' => null, 'scope' => [], 'attrs' => []];
        $this->_action = $action;
        $this->_attributes = $options['attrs'];
        $this->scope = $options['scope'];
        $this->_url = $options['url'];
        $this->label = $options['label'] ?: Inflector::humanize($this->_action);
    }

    /**
     * @inheritDoc
     */
    public function getName() {
        return $this->_action;
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->_attributes;
    }

    /**
     * @inheritDoc
     */
    public function getUrl($id): array|string
    {
        return $this->_buildUrl(['id' => $id]);
    }

    /**
     * @inheritDoc
     */
    protected function _execute(Controller $controller): ?Response
    {
        $redirectUrl = $this->_buildUrl();

        return $controller->redirect($redirectUrl);
    }

    /**
     * @inheritDoc
     */
    protected function _buildUrl($data = null)
    {
        if ($data === null) {
            $data = $this->entity()->toArray();
        }

        return $this->_replaceTokens($this->_url, $data);
    }
}
