<?php

use ProjectModel\Atom;

$atomButton = (new Atom('button'))
    ->AddProp('title')
    ->AddProp('className', "'btn'")
    ->AddProp('isLoading', "false")
    ->AddProp('disabled', "false")
    ->AddProp('icon')
    ->AddCallback('onClick', "null");

$atomInput = (new Atom('input'))
    ->HasLabel()
    ->AddProp('className')
    ->AddProp('label')
    ->AddProp('type', "'text'", "text, password")
    ->AddProp('placeholder')
    ->AddProp('value')
    ->AddProp('help')
    ->AddProp('hasError', "'unknown'", "'yes', 'no', 'unknown'")
    ->AddProp('error')
    ->AddProp('maxLength', "null")
    ->AddProp('disabled', "false")
    ->AddProp('mandatory', "false")
    ->AddState('value')
    ->AddState('availableCount')
    ->AddCallback('onTest', "null");

$atomCheckbox = (new Atom('checkbox'))
    ->HasLabel()
    ->AddProp('className')
    ->AddProp('label')
    ->AddProp('checked')
    ->AddProp('help')
    ->AddProp('hasError', "'unknown'", "'yes', 'no', 'unknown'")
    ->AddProp('error')
    ->AddProp('disabled', "false")
    ->AddProp('mandatory', "false")
    ->AddState('checked');

$atomTextarea = (new Atom('textarea'))
    ->HasLabel()
    ->AddProp('className')
    ->AddProp('label')
    ->AddProp('placeholder')
    ->AddProp('value')
    ->AddProp('help')
    ->AddProp('hasError', "'unknown'", "'yes', 'no', 'unknown'")
    ->AddProp('error')
    ->AddProp('maxLength', "null")
    ->AddProp('rows', "3")
    ->AddProp('resizable', "false")
    ->AddProp('disabled', "false")
    ->AddProp('mandatory', "false")
    ->AddState('value')
    ->AddState('availableCount')
    ->AddCallback('onTest', "null");

$atomSelect = (new Atom('select'))
    ->HasLabel()
    ->AddProp('className')
    ->AddProp('label')
    ->AddProp('value')
    ->AddProp('optionData', "[]")
    ->AddProp('help')
    ->AddProp('hasError', "'unknown'", "'yes', 'no', 'unknown'")
    ->AddProp('error')
    ->AddProp('disabled', "false")
    ->AddProp('mandatory', "false")
    ->AddState('value');

$atomSelectMenuItem = (new Atom('select menu item'))
    ->AddProp('className')
    ->AddProp('status', "'new'", "'new', 'done'")
    ->AddProp('value')
    ->AddProp('content', "[]")
    ->AddProp('hasError', "'unknown'", "'yes', 'no', 'unknown'")
    ->AddProp('key')
    ->AddState('value')
    ->AddCallback('onAction', "null");

