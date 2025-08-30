import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import ID from './ids';
import i18n from '../../shared/i18n/index';

import Select from '../../atom/select';

export default class TwoBindSelect {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, label, value, optionData, onReady = null } = settings;

        this._prop = {
            langId,
            label,
            value,
            optionData,
        };

        this._state = {
            value,
        };

        this._callback = {
            onReady
        }

        this._stateFirstSelect = {};
        this._atm.firstSelect = <Select label={i18n(langId, label.first)} value={value.first} onChange={this.onChangeFirst}></Select>;
        this._updateStateSelect(ID.TBS_SELECT_FIRST_ID, {
            optionData: optionData.first,
        });

        this._stateSecondSelect = {};
        this._atm.secondSelect = <Select label={i18n(langId, label.second)} value={value.second} onChange={this.onChangeSecond}></Select>;
        this._updateStateSelect(ID.TBS_SELECT_SECOND_ID, {
            optionData: optionData.second[value.first],
        });

        this.el = this._ui_render();
    }

    _updateStateSelect = (entity, state) => {
        const { value = null, optionData = null } = state;

        let stateNameSelect;
        let nameSelect;
        switch (entity) {
            case ID.TBS_SELECT_FIRST_ID:
                stateNameSelect = this._stateFirstSelect;
                nameSelect = this._atm.firstSelect;
                break;
            case ID.TBS_SELECT_SECOND_ID:
                stateNameSelect = this._stateSecondSelect;
                nameSelect = this._atm.secondSelect;
                break;
            default:
                return;
        }

        stateNameSelect['value'] = value ?? stateNameSelect.value;
        stateNameSelect['optionData'] = optionData ?? stateNameSelect.optionData;

        if (value !== null) {
            nameSelect.updateProp('value', value);
        }
        if (optionData !== null) {
            nameSelect.updateProp('optionData', optionData);
        }
    };

    getState = (name) => {
        return this._state[name];
    };

    onChangeFirst = (oldValue, newValue) => {
        this._updateStateSelect(ID.TBS_SELECT_FIRST_ID, {
            value: newValue,
        });

        const { optionData } = this._prop;

        this._updateStateSelect(ID.TBS_SELECT_SECOND_ID, {
            value: '0',
            optionData: optionData.second[newValue],
        });

        this._state.value = {
            first: newValue,
            second: '0',
        };
    };

    onChangeSecond = (oldValue, newValue) => {
        let { value } = this._state;

        this._updateStateSelect(ID.TBS_SELECT_SECOND_ID, {
            value: newValue,
        });

        value.second = newValue;
        this._state.value = value;

        if (value.first !== '0' && value.second !== '0') {
            const {onReady} = this._callback;
            onReady && onReady();
        }
    };

    _ui_render = () => {
        return (
            <div className="d-flex gap-4">
                {this._atm.firstSelect}
                {this._atm.secondSelect}
            </div>
        );
    };
}
