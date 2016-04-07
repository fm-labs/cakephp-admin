<?php
return [
    /**
     * Semantic UI
    'button' => '<button{{attrs}}>{{text}}</button>',
    //'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',
    //'checkboxFormGroup' => '{{label}}',
    //'checkboxWrapper' => '<div class="checkbox">{{label}}</div>',
    'inputSubmit' => '<input class="ui primary button" type="{{type}}"{{attrs}}>',
    'inputContainer' => '<div class="field input-{{type}}{{required}}">{{content}}</div>',
    'inputContainerError' => '<div class="field input-{{type}}{{required}} error">{{content}}{{error}}</div>',
    'submitContainer' => '<div class="field submit">{{content}}</div>',
     */

    /*
    'button' => '<button{{attrs}}>{{text}}</button>',
    'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',
    'checkboxFormGroup' => '{{label}}',
    'checkboxWrapper' => '<div class="checkbox">{{label}}</div>',
    'dateWidget' => '{{year}}{{month}}{{day}}{{hour}}{{minute}}{{second}}{{meridian}}',
    'error' => '<div class="error-message">{{content}}</div>',
    'errorList' => '<ul>{{content}}</ul>',
    'errorItem' => '<li>{{text}}</li>',
    'file' => '<input type="file" name="{{name}}"{{attrs}}>',
    'fieldset' => '<fieldset{{attrs}}>{{content}}</fieldset>',
    'formStart' => '<form{{attrs}}>',
    'formEnd' => '</form>',
    'formGroup' => '{{label}}{{input}}',
    'hiddenBlock' => '<div style="display:none;">{{content}}</div>',
    'input' => '<input type="{{type}}" name="{{name}}"{{attrs}}/>',
    'inputSubmit' => '<input type="{{type}}"{{attrs}}/>',
    'inputContainer' => '<div class="input {{type}}{{required}}">{{content}}</div>',
    'inputContainerError' => '<div class="input {{type}}{{required}} error">{{content}}{{error}}</div>',
    'label' => '<label{{attrs}}>{{text}}</label>',
    'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
    'legend' => '<legend>{{text}}</legend>',
    'option' => '<option value="{{value}}"{{attrs}}>{{text}}</option>',
    'optgroup' => '<optgroup label="{{label}}"{{attrs}}>{{content}}</optgroup>',
    'select' => '<select name="{{name}}"{{attrs}}>{{content}}</select>',
    'selectMultiple' => '<select name="{{name}}[]" multiple="multiple"{{attrs}}>{{content}}</select>',
    'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}}>',
    'radioWrapper' => '{{label}}',
    'textarea' => '<textarea name="{{name}}"{{attrs}}>{{value}}</textarea>',
    'submitContainer' => '<div class="submit">{{content}}</div>',
    */

    'error' => '<span class="help-block">{{content}}</span>',
    'inputContainer' => '<div class="form-group {{required}}">{{content}}</div>',
    'inputContainerError' => '<div class="form-group has-error {{required}}">{{content}}{{error}}</div>',

    'fieldsetBody' => '<div class="body"{{attrs}}>{{content}}</div>',
];