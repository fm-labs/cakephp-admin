<?php
namespace Backend\Controller\Admin;

use Cake\Controller\Component\AuthComponent;
use Cake\Controller\Component\PaginatorComponent;
use Backend\Controller\Base\BaseBackendController;
use Cake\Core\Configure;
use Backend\Controller\Component\FlashComponent;


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
}
