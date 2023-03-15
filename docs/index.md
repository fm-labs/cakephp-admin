# Developer Documentation


## Authentication

@todo

## Routing / Request handling

### Middleware
* AdminMiddleware - Detect admin requests and hook admin plugins

### Policies

* AdminPolicy - Access policy for root user
* AdminRequestPolicy - Access policy for admin users

## Actions

* ActionComponent - Manage and invoke generic Actions
* ActionInterface
* EntityActionInterface
* IndexActionInterface
* BaseAction
* BaseEntityAction
* BaseIndexAction


### Built-in actions

* AddAction
* CopyAction
* DashboardAction
* DataTableIndexAction
* DebugAction
* DeleteAction
* EditAction
* ExternalEntityAction
* FooTableIndexAction
* IndexAction
* IndexFilterAction
* InlineEntityAction
* ManageAction
* PublishAction
* RedirectAction
* SearchAction
* TreeIndexAction
* TreeMoveDownAction
* TreeMoveUpAction
* TreeRepairAction
* TreeSortAction
* UnpublishAction
* ViewAction


## DebugKit support

* AdminPanel - Custom debugkit panel



## Controllers

* AuthController - Admin authentication
* CacheController - Manage CakePHP system caches
* DesignController - Appearance kitchen sink
* HealthController - Perform health checks on application and plugins
* LogsController - Manage CakePHP system logs
* PluginsController - Manage CakePHP/Cupcake plugins
* SystemController - Display various system information

* DataTablesController - Support controller for DataTables integration
* SimpleTreeController - Support controller for "Tree"-operations on models
* TreeController - Support controller for "Tree"-operations on models


## Behaviors

* JsTreeBehavior - Support for "Tree"-operations on models


## Admin Core

### Dispatching

* ActionDispatcherListener - Magic for 'Action' dispatching

### Admin plugins

* AdminPluginCollection - Internal collection of attached Admin plugins
* AdminPluginInterface - Interface for Admin plugins
* BaseAdminPlugin - Abstract base implementation of an Admin plugin

### Admin Utils

* AdminNotifier - Email notifications for admins

### Admin Services

* AdminService - Abstract base implementation of an Admin service
* ServiceRegistry - Registry for admin services

#### Built-in services

* CrudService - Automatically register CRUD actions for controllers with `scaffold` property set.
* PublishService - Automatically register publishing actions for controllers with a primary model using the `Publishable` behavior.
* TreeService - Automatically register tree-operation actions for controllers with a primary model using the `Tree` behavior.


## View

* AdminView - Init core helpers and UI. Overrides `fetch` method to render UI elements.

### UI

* Layout
  * Header
    * MenuPanel (Bootstrap Menu dropdown)
    * SearchPanel
    * UserPanel
  * Sidebar
    * MenuPanel (Bootstrap Menu with custom templates)
  * Footer


## Helpers

* AdminHelper - Load admin assets
  * Load theme configuration
  * Configure 3rd party assets
  * Init admin helpers
  * Load AdminJs scripts
    * iconify
    * tooltip
    * checkauth
* AdminFormHelper - Setup widgets for admin forms
* AjaxHelper - Load content via Ajax
* BreadcrumbHelper - Admin breadcrumbs
* ToolbarHelper - Admin actions toolbar


### Admin layout sections

* @head
  * title
  * meta
  * css
  * headjs
* @body
  * header
  * sidebar
  * flash
  * breadcrumbs
  * toolbar
  * top
  * left
  * right
  * before
  * content
  * after
  * bottom
  * footer
  * script

### Admin view vars

* be_title
* be_dashboard_url
* be_layout_body_class

### Admin theme configuration

* Admin.Theme.name
* Admin.Theme.skin
* Admin.Theme.darkMode
* Admin.Theme.bodyClass
* Admin.Theme.enableJsFlash (uses toastr)
* Admin.Theme.enableJsAlerts (uses sweet alert2)


## Shell / CLI

* ~~AdminShell~~

### Tasks

* RootUserTask - Create root user with login credentials