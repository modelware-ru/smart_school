import { el, mount } from '../../node_modules/redom/dist/redom.es';
import { clsx } from '../../node_modules/clsx/dist/clsx.mjs';

import Atom from './atom';

export default class TableRow extends Atom {
    // start "constructor"
    constructor(settings = {}) {
        super();
        const { className = '', content = [], key = '', onRowClick = null } = settings;

        this._prop = {
            className,
            content,
            key,
        };

        this._state = {};

        this._callback = {
            onRowClick,
        };

        this.el = this._ui_render();
    }
    // finish "constructor"

    // start "_renderProp"
    _renderProp = (name, value) => {
        let { row: _ui_row } = this._el;

        switch (name) {
            // start "className"
            case 'className':
                _ui_row.className = value;
                break;
            // finish "className"
            // start "content"
            case 'content':
                break;
            // finish "content"
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
        // let { ???: _ui_??? } = this._el;

        switch (name) {
            default:
                return;
        }
    };
    // finish "_renderState"

    _onRowClick = (e) => {
        e.stopPropagation();

        const { onRowClick } = this._callback;
        const { key } = this._prop;

        onRowClick && onRowClick(key);
    };

    // start "_ui_render"
    _ui_render = () => {
        const { className, content, key } = this._prop;

        return (this._el.row = (
            <tr className={className} onclick={this._onRowClick}>
                {content.map((item) => {
                    return <td className={item['className'] ?? ''}>{item['value']}</td>;
                })}
            </tr>
        ));
    };
    // finish "_ui_render"
}
