# Admin View Helpers


## FormatterHelper

The `FormatterHelper` holds a collection of convenience data formatter helpers,
primarily used as cell formatters in datatables.

Formatters MUST have following call signature:
```php
/**
 * @params mixed $val The cell data value
 * @params mixed $extra The cell row data
 * @params array $params The formatter params
 * @params \Cake\View\View $view The View instance
 */
function (mixed $val, mixed $extra, array $params, \Cake\View\View $view): string {
    return ""
}
```


### Built-in formatters


#### escape

Html escapes the value

#### boolean

The value is interpreted as boolean and mapped to 'Yes'/'No' strings.

If `Cupcake` plugin is loaded, instead of the string outputs
the Cupcake's `StatusHelper::boolean($val, array $options = [])` method will be called, with 
 `formatterArgs` as second argument.

#### date

Format value as date string

#### datetime

Format value as datetime string

#### link

Format value as HTML link

#### number

Format value as number

#### truncate

Truncate value

#### currency

Format value as currency

#### email

Format value as email mailto link

#### array


#### json

