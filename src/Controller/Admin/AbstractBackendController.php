<?php
namespace Backend\Controller\Admin;

use Backend\Action\ActionInterface;
use Backend\Controller\BackendActionsTrait;
use Cake\Controller\Component\AuthComponent;
use Cake\Controller\Component\PaginatorComponent;
use Backend\Controller\Base\BaseBackendController;
use Cake\Controller\Exception\MissingActionException;
use Cake\Core\App;
use Cake\Core\Configure;
use Backend\Controller\Component\FlashComponent;
use Cake\Network\Exception\NotFoundException;
use RuntimeException;


/**
 * Class BackendAppController
 *
 * Use this class as a base controller for app controllers
 * which should run in backend context
 *
 * @package Backend\Controller
 *
 * @property AuthComponent $Auth
 * @property FlashComponent $Flash
 * @property PaginatorComponent $Paginator
 *
 * @deprecated Use Backend\Controller\Base\BaseBackendController instead;
 */
abstract class AbstractBackendController extends BaseBackendController
{
    use BackendActionsTrait;

    public $actions = [
        'index' => 'Backend.Index',
        'view' => 'Backend.View',
        'search' => [
            'className' => 'Backend.Search',
            'foo' => 'bar'
        ]
    ];
}