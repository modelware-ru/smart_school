<?php
    global $atom;
?>
import { el, mount } from '../../node_modules/redom/dist/redom.es';
import { clsx } from '../../node_modules/clsx/dist/clsx.mjs';

import Atom from './atom';

export default class <?= $atom->className ?> extends Atom {
<?php
if ($atom->hasLabel) {
    echo PHP_EOL;
?>
    labelFor = 'l' + new Date().getTime() + Math.random();
<?php
}
echo PHP_EOL;
?>
    // start "constructor"
    constructor(settings = {}) {
        super();
        const {
<?php
foreach ($atom->propList as $prop) {
?>
            <?= $prop['name'] ?> = <?= $prop['default'] ?>,<?= empty($prop['comment']) ? "" : " // {$prop['comment']}"  ?>
<?php
    echo PHP_EOL;
}
foreach ($atom->callbackList as $callback) {
?>
            <?= $callback['name'] ?> = <?= $callback['default'] ?>,<?= empty($callback['comment']) ? "" : " // {$callback['comment']}"  ?>
<?php
    echo PHP_EOL;
}    
?>
        } = settings;

        this._prop = {
<?php
foreach ($atom->propList as $prop) {
?>
            <?= $prop['name'] ?>,
<?php
}
?>
        };

        this._state = {
<?php
foreach ($atom->stateList as $state) {
?>
            <?= $state['name'] ?>,
<?php
}
?>
        };

        this._callback = {
<?php
foreach ($atom->callbackList as $callback) {
?>
            <?= $callback['name'] ?>,
<?php
}
?>
        };

        this.el = this._ui_render();
    }
    // finish "constructor"

    // start "_renderProp"
    _renderProp = (name, value) => {
        // let { ???: _ui_??? } = this._el;

        switch (name) {
<?php
foreach ($atom->propList as $prop) {
?>
            // start "<?= $prop['name']?>"
            case '<?= $prop['name']?>':
                break;
            // finish "<?= $prop['name']?>"
<?php
}
?>
            default:
                return;
        }
    };
    // finish "_renderProp"

    // start "_renderState"
    _renderState = (name, value) => {
        // let { ???: _ui_??? } = this._el;

        switch (name) {
<?php
foreach ($atom->stateList as $state) {
?>
            // start "<?= $state['name']?>"
            case '<?= $state['name']?>':
                break;
            // finish "<?= $state['name']?>"
<?php
}
?>
            default:
                return;
        }
    };
    // finish "_renderState"

    // start "_ui_render"
    _ui_render = () => {
        const { 
<?php
foreach ($atom->propList as $prop) {
?>
            <?= $prop['name'] ?>,
<?php
}
?>
        } = this._prop;
<?php
if (!empty($atom->stateList)) {
?>
        const {
<?php
    foreach ($atom->stateList as $state) {
?>
            <?= $state['name'] ?>,
<?php
    }
?>
        } = this._state;
<?php
}
?>

        this._el = null;
        return this._el;
    };
    // finish "_ui_render"
}
