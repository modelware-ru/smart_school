import { el, mount } from '../../node_modules/redom/dist/redom.es';
import { clsx } from '../../node_modules/clsx/dist/clsx.mjs';

import Atom from './atom';

export default class Button extends Atom {

    // start "constructor"
    constructor(settings = {}) {
        super();
        const {
            title = '',
            className = 'btn',
            isLoading = false,
            disabled = false,
            icon = '',
            onClick = null,
        } = settings;

        this._prop = {
            title,
            className,
            isLoading,
            disabled,
            icon,
        };

        this._state = {
        };

        this._callback = {
            onClick,
        };

        this.el = this._ui_render();
    }
    // finish "constructor"

    // start "_renderProp"
    _renderProp = (name, value) => {
        let { btn: _ui_btn, btnTitle: _ui_btnTitle, spinner: _ui_spinner, icon: _ui_icon } = this._el;

        switch (name) {
            // start "title"
            case 'title':
                _ui_btnTitle.innerText = value;
                break;
            // finish "title"
            // start "className"
            case 'className':
                break;
            // finish "className"
            // start "isLoading"
            case 'isLoading':
                this._el.spinner = mount(_ui_btn, this._ui_spinner(), _ui_spinner, true);
                break;
            // finish "isLoading"
            // start "disabled"
            case 'disabled':
                if (value) {
                    _ui_btn.setAttribute('disabled', '');
                } else {
                    _ui_btn.removeAttribute('disabled');
                }
                break;
            // finish "disabled"
            // start "icon"
            case 'icon':
                this._el.icon = mount(_ui_btn, this._ui_icon(), _ui_icon, true);
                break;
            // finish "icon"
            default:
                return;
        }
    };
    // finish "_renderProp"

    // start "_renderState"
    _renderState = (name, value) => {
        // let { ???: _ui_??? } = this._el;

        switch (name) {
            default:
                return;
        }
    };
    // finish "_renderState"

    _onClick = (e) => {
        const { onClick } = this._callback;
        onClick && onClick();
    };

    _ui_spinner = () => {
        const { isLoading } = this._prop;
        return isLoading ? <span className="spinner-border spinner-border-sm me-1" aria-hidden="true"></span> : <span></span>;
    };

    _ui_icon = () => {
        const { icon, title } = this._prop;
        if (icon.length === 0) return <i></i>;

        if (title.length === 0) {
            return <i class={'bi ' + icon}></i>;
        } else {
            return <i class={'bi ' + icon + ' me-2'}></i>;
        }
    };

    // start "_ui_render"
    _ui_render = () => {
        const { 
            title,
            className,
            isLoading,
            disabled,
            icon,
        } = this._prop;

        this._el.btn = (
            <button type="button" className={className} disabled={disabled} onclick={this._onClick}>
                {(this._el.spinner = this._ui_spinner(isLoading))}
                {(this._el.icon = this._ui_icon(icon))}
                {(this._el.btnTitle = <span role="status">{title}</span>)}
            </button>
        );
        return this._el.btn;
    };
    // finish "_ui_render"
}
