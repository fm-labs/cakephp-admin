<?php
return [
    'button' => '<button{{attrs}}>{{text}}</button>',
    //'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',
    //'checkboxFormGroup' => '{{label}}',
    //'checkboxWrapper' => '<div class="checkbox">{{label}}</div>',
    'inputSubmit' => '<input class="ui primary button" type="{{type}}"{{attrs}}>',
    'inputContainer' => '<div class="field input-{{type}}{{required}}">{{content}}</div>',
    'inputContainerError' => '<div class="field input-{{type}}{{required}} error">{{content}}{{error}}</div>',
    'submitContainer' => '<div class="field submit">{{content}}</div>',
];