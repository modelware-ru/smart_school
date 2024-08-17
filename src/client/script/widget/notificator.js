import { el, mount } from '../../node_modules/redom/dist/redom.es';
import { clsx } from '../../node_modules/clsx/dist/clsx.mjs';

import i18n from '../shared/i18n/index';
import { commonEventManager, EventManager } from '../shared/eventManager';

export default class Notificator {
    _el = {};

    constructor(settings = {}) {
        const { langId = 'ru', message = null, show = false, fixed = null } = settings;

        this._prop = {
            langId,
            fixed,
        };

        this._state = {
            message,
            show,
        };

        this.el = this._ui_render();

        this._updateState(this._state);

        commonEventManager.subscribe('showMessage', this._showMessage);
        commonEventManager.subscribe('hideMessage', this._hideMessage);
    }

    _showMessage = (message) => {
        if (this._state.show) {
            this._updateState({ message });
        } else {
            this._updateState({ show: true, message });
        }
    };

    _hideMessage = () => {
        if (!this._state.show) return;

        this._updateState({ show: false });
    };

    _updateState = (state) => {
        const { message = null, show = null } = state;
        const { langId } = this._prop;

        this._state = {
            message: message ?? this._state.message,
            show: show ?? this._state.show,
        };

        if (message !== null) {
            const text = i18n(langId, message.code, message.args);
            if (typeof text === 'string') {
                this._el.content.innerText = i18n(langId, message.code, message.args);
            } else {
                this._el.content = mount(this._el.widget, text, this._el.content, true);
            }
        }

        if (show !== null) {
            if (show) {
                this._el.widget.classList.remove('d-none');
            } else {
                this._el.widget.classList.add('d-none');
            }
        }
    };

    _ui_render = () => {
        const { fixed } = this._prop;

        this._el.widget = (
            <div
                class={clsx(
                    'alert alert-danger alert-dismissible rounded-0 text-center m-0 fade show',
                    { 'fixed-top': fixed === 'top' },
                    { 'fixed-bottom': fixed === 'bottom' }
                )}
                role="alert"
            >
                {(this._el.content = <p class="m-0"></p>)}
                <button type="button" class="btn-close" aria-label="Close" onclick={this._hideMessage}></button>
            </div>
        );

        return this._el.widget;
    };
}
