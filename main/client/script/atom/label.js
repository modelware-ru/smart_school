import { el, mount } from '../../node_modules/redom/dist/redom.es';
import { clsx } from '../../node_modules/clsx/dist/clsx.mjs';

import Atom from './atom';

export default class Label extends Atom {
    // start "constructor"
    constructor(settings = {}) {
        super();
        const { title = '', className = '', iconClassName = '', disabled = false, icon = '', onClickData = {}, onClick = null } = settings;

        this._prop = {
            title,
            className,
            iconClassName,
            disabled,
            icon,
            onClickData,
        };

        this._state = {};

        this._callback = {
            onClick,
        };

        this.el = this._ui_render();
    }
    // finish "constructor"

    // start "_renderProp"
    _renderProp = (name, value) => {
        let { label: _ui_label, icon: _ui_icon } = this._el;

        switch (name) {
            // start "title"
            case 'title':
                break;
            // finish "title"
            // start "className"
            case 'className':
                this._el.label.className = this._labelClassName();
                break;
            // finish "className"
            // start "iconClassName"
            case 'iconClassName':
                this._el.icon.className = this._iconClassName();
                break;
            // finish "className"
            // start "disabled"
            case 'disabled':
                if (value) {
                    _ui_label.setAttribute('disabled', '');
                } else {
                    _ui_label.removeAttribute('disabled');
                }
                break;
            // finish "disabled"
            // start "icon"
            case 'icon':
                this._el.icon = mount(_ui_label, this._ui_icon(), _ui_icon, true);
                break;
            // finish "icon"
            // start "onClickData"
            case 'onClickData':
                this._prop['onClickData'] = value;
                break;
            // finish "onClickData"
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
        e.stopPropagation();
        const { onClickData } = this._prop;
        const { onClick } = this._callback;
        onClick && onClick(onClickData);
    };

    _ui_icon = () => {
        const { icon } = this._prop;
        if (icon.length === 0) return <i></i>;

        return <i className={this._iconClassName()}></i>;
    };

    _iconClassName = () => {
        const { icon, iconClassName } = this._prop;
        return clsx('bi', 'ms-2', icon, iconClassName);
    };

    _labelClassName = () => {
        const { className } = this._prop;
        return clsx(className, 'btn btn-sm text-nowrap');
    };

    // start "_ui_render"
    _ui_render = () => {
        const { disabled, title } = this._prop;

        this._el.label = (
            <div className={this._labelClassName()} disabled={disabled} onclick={this._onClick}>
                <span>{title}</span>
                {(this._el.icon = this._ui_icon())}
            </div>
        );
        return this._el.label;
    };
    // finish "_ui_render"
}
