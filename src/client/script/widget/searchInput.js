import { el, mount } from '../../node_modules/redom/dist/redom.es';
import { clsx } from '../../node_modules/clsx/dist/clsx.mjs';

import Input from '../atom/input';

import i18n from '../shared/i18n/index';
import { commonEventManager, EventManager } from '../shared/eventManager';

export default class SearchInput {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId = 'ru' } = settings;

        this._prop = {
            langId,
        };

        this._state = {};

        this._stateSearchInput = {};
        this._atm.searchInput = <Input className="input-group" type="search" placeholder={i18n(langId, 'TTL_SEARCH')} iconBefore={'bi-search'} onTest={this._onSearchTest} />;
        this._updateSearchInput({
            disabled: true,
            hasError: 'unknown',
        });

        this.el = this._ui_render();
    }

    _updateSearchInput = (state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        this._stateSearchInput = {
            disabled: disabled ?? this._stateSearchInput.disabled,
            hasError: hasError ?? this._stateSearchInput.hasError,
            error: error ?? this._stateSearchInput.error,
        };

        if (disabled !== null) {
            this._atm.searchInput.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            this._atm.searchInput.updateProp('hasError', hasError);
        }
        if (error !== null && this._atm.searchInput.getProp('error') !== i18n(langId, error.code, error.args)) {
            this._atm.searchInput.updateProp('error', i18n(langId, error.code, error.args));
        }
    };

    _onSearchTest = (value) => {
        if (value.length > 3) {
            console.log(value);
        }
        return true;
    }

    _ui_render = () => {
        return <div className="d-flex flex-fill">{this._atm.searchInput}</div>;
    };
}
