import { el, mount } from '../../node_modules/redom/dist/redom.es';
import { clsx } from '../../node_modules/clsx/dist/clsx.mjs';

import i18n from '../shared/i18n/index';
import { commonEventManager } from '../shared/eventManager';

export default class Spinner {
    _el = {};

    constructor(settings = {}) {
        const { langId = 'ru', show = false } = settings;

        this._prop = {
            langId,
        };

        this._state = {
            show,
        };

        this.el = this._ui_render();

        this._updateState(this._state);

        commonEventManager.subscribe('showSpinner', this._showSpinner);
        commonEventManager.subscribe('hideSpinner', this._hideSpinner);
    }

    _showSpinner = () => {
        if (this._state.show) return;

        this._updateState({ show: true });
    };

    _hideSpinner = () => {
        if (!this._state.show) return;

        this._updateState({ show: false });
    };

    _updateState = (state) => {
        const { show = null } = state;

        this._state = {
            show: show ?? this._state.show,
        };

        if (show !== null) {
            if (show) {
                this._el.widget.classList.remove('d-none');
            } else {
                this._el.widget.classList.add('d-none');
            }
        }
    };

    _ui_render = () => {
        this._el.widget = (
            <div className="position-absolute bg-body-secondary w-100 h-100 top-0 start-0 d-flex justify-content-center align-items-center bg-opacity-50">
                <div className="spinner-border" role="status"></div>
            </div>
        );

        return this._el.widget;
    };
}
