# ViewAction

## Configuration

```php
    protected $_defaultConfig = [
        'label' => null,
        'entity' => null,
        'entityOptions' => [],
        'modelClass' => null,
        'modelId' => null,
        'fields' => [],
        'fields.whitelist' => [],
        'fields.blacklist' => [],
        'related' => [],
        'viewOptions' => [], // deprecated
        'actions' => [],
        'tabs' => [],
        'helpers' => [],
    ];

// deprecated viewOptions
$this->_config['viewOptions']['model'] = $this->_config['modelClass'];
$this->_config['viewOptions']['title'] = $entity->get($this->model()->getDisplayField());
$this->_config['viewOptions']['debug'] = Configure::read('debug');
$this->_config['viewOptions']['fields'] = $this->_config['fields'];
$this->_config['viewOptions']['whitelist'] = $this->_config['fields.whitelist'];
$this->_config['viewOptions']['blacklist'] = $this->_config['fields.blacklist'];
$this->_config['viewOptions']['helpers'] = $this->_config['helpers'];
```

* string `label`: Action display label (used in Menus and Headings)
* EntityInterface `entity`: Model entity instance 
* array `entityOptions`: Model entity finder options  
* string `modelClass`: Model class name  
* string `modelId`: Model Id
* array `fields`: Field definitions  
* array `fields.whitelist`: Fields included when rendering  
* array `fields.blacklist`: Fields excluded when rendering
* array `related`: Inline index view configuration for related models 
* array `viewOptions`: Options passed to the EntityView cell  
  * string `model`
  * string `title`
  * bool `debug`
  * array `fields`
  * array `whitelist`
  * array `blacklist`
  * array `helpers`
* array `actions`: Actions rendering definitions  
* array `tabs`: Tabs rendering definitions 
* array `helpers`: Preloaded View helpers  
