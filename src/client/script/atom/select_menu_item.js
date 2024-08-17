import { el, mount } from '../../node_modules/redom/dist/redom.es';
import { clsx } from '../../node_modules/clsx/dist/clsx.mjs';

import Atom from './atom';
import Button from './button';

export default class SelectMenuItem extends Atom {
    // start "constructor"
    constructor(settings = {}) {
        super();
        const {
            className = '',
            status = 'new', // 'new', 'done'
            value = '',
            content = [],
            hasError = 'unknown', // 'yes', 'no', 'unknown'
            key = '',
            onAction = null,
        } = settings;

        this._prop = {
            className,
            status,
            value,
            content,
            hasError,
            key,
        };

        this._state = {
            value,
        };

        this._callback = {
            onAction,
        };

        this.el = this._ui_render();
    }
    // finish "constructor"

    // start "_renderProp"
    _renderProp = (name, value) => {
        let { select: _ui_select, menuitem: _ui_menuitem, btn: _ui_btn } = this._el;

        switch (name) {
            // start "className"
            case 'className':
                break;
            // finish "className"
            // start "status"
            case 'status':
                if (value === 'done') {
                    _ui_select.setAttribute('disabled', '');
                    _ui_select.classList.remove('is-invalid');
                    _ui_select.classList.add('is-valid');
                    this.el.btn = mount(_ui_menuitem, this._ui_button(), _ui_btn, true);
                } else {
                    _ui_select.classList.remove('is-valid');
                    _ui_select.classList.add('is-invalid');
                    _ui_select.removeAttribute('disabled');
                    this.el.btn = mount(_ui_menuitem, this._ui_button(), _ui_btn, true);
                }
                break;
            // finish "status"
            // start "value"
            case 'value':
                break;
            // finish "value"
            // start "content"
            case 'content':
                break;
            // finish "content"
            // start "hasError"
            case 'hasError':
                break;
            // finish "hasError"
            // start "key"
            case 'key':
                break;
            // finish "key"
            default:
                return;
        }
    };
    // finish "_renderProp"

    // start "_renderState"
    _renderState = (name, value) => {
        const { select: _ui_select } = this._el;

        switch (name) {
            // start "value"
            case 'value':
                _ui_select.value = value;
                break;
            // finish "value"
            default:
                return;
        }
    };
    // finish "_renderState"

    _onChange = (e) => {
        const { value: oldValue } = this._state;
        const newValue = e.target.value;

        if (oldValue === newValue) return;

        this._updateState('value', newValue);
    };

    _inputClassName = () => {
        const { hasError } = this._prop;
        return clsx('form-select', hasError === 'yes' && 'is-invalid', hasError === 'no' && 'is-valid');
    };

    _onButtonClick = (e) => {
        const { key, status } = this._prop;
        const { value } = this._state;
        const { onAction } = this._callback;

        if (value !== '0') {
            onAction && onAction({ key, value, status });
        }
    };

    _ui_button = () => {
        const { status } = this._prop;

        if (status === 'new') {
            return <Button className="btn btn-outline-success bg-success-subtle ms-4" icon={'bi-plus-circle'} onClick={this._onButtonClick} />;
        }
        if (status === 'done') {
            return <Button className="btn btn-outline-danger bg-danger-subtlee ms-4" icon={'bi-trash'} onClick={this._onButtonClick} />;
        }

        return null;
    };

    // start "_ui_render"
    _ui_render = () => {
        const {
            className,
            status,
            // value,
            content,
            hasError,
            key,
        } = this._prop;
        const { value } = this._state;

        const optionList = content.map((item) => {
            return (
                <option value={item['value']} disabled={item['disabled']} selected={item['value'] === value}>
                    {item['name']}
                </option>
            );
        });

        this._el.select = (
            <select className={this._inputClassName()} disabled={status === 'done'} value={value} onchange={this._onChange}>
                {optionList}
            </select>
        );

        return (this._el.menuitem = (
            <div class={clsx(className, 'd-flex')}>
                {this._el.select}
                {(this._el.btn = this._ui_button())}
            </div>
        ));
    };
    // finish "_ui_render"
}
