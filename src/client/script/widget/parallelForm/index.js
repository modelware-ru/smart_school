import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import ID from './ids';
import i18n from '../../shared/i18n/index';
import Input from '../../atom/input';
import Checkbox from '../../atom/checkbox';
import Button from '../../atom/button';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class ParallelForm {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, parallel } = settings;

        this._prop = {
            langId,
            parallelId: parallel.id,
        };

        this._state = {};

        this._stateNameInput = {};
        this._atm.nameInput = <Input className="col-12" label={i18n(langId, 'TTL_PARALLEL_NAME')} value={parallel.name} mandatory maxLength={100} />;
        this._updateStateNameInput({
            disabled: false,
            hasError: 'unknown',
        });

        this._stateNumberInput = {};
        this._atm.numberInput = (
            <Input className="col-12" label={i18n(langId, 'TTL_PARALLEL_NUMBER')} value={parallel.number} mandatory maxLength={10} />
        );
        this._updateStateNumberInput({
            disabled: false,
            hasError: 'unknown',
        });

        this._stateShowInGroupCheckbox = {};
        this._atm.showInGroupCheckbox = (
            <Checkbox className="col-12" label={i18n(langId, 'TTL_PARALLEL_SHOW_IN_GROUP')} checked={parallel.showInGroup} />
        );
        this._updateStateShowInGroupCheckbox({
            disabled: false,
            hasError: 'unknown',
        });

        this._stateSaveButton = {};
        this._atm.saveButton = <Button className="btn btn-success" onClick={this._onSaveButtonClick} />;
        this._updateStateSaveButton({
            disabled: false,
            title: 'TTL_TO_SAVE',
            isLoading: false,
            icon: 'bi-floppy',
        });

        this.el = this._ui_render();
    }

    _onSaveButtonClick = () => {
        const name = this._atm.nameInput.getState('value');
        const number = this._atm.numberInput.getState('value');
        const showInGroup = this._atm.showInGroupCheckbox.getState('checked');

        const { hasError, data } = this._validateFormData(name, number);

        this._showError({ status: hasError ? 'fail' : 'ok', data });

        commonEventManager.dispatch('hideMessage');

        if (!hasError) {
            const { parallelId } = this._prop;

            this._callSaveParallel({ id: parallelId, name, number, showInGroup });
        }
    };

    _onCancelButtonClick = () => {
        history.back();
    }

    _validateFormData = (name, number) => {
        let data = {};
        let hasError = false;
        if (name.length === 0) {
            data[ID.PF_INPUT_NAME_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }

        if (number.length === 0) {
            data[ID.PF_INPUT_NUMBER_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }
        return { hasError, data };
    };

    _showError = ({ status, data }) => {
        if (status === 'ok') {
            this._updateStateNameInput({ disabled: false, hasError: 'no', error: null });
            this._updateStateNumberInput({ disabled: false, hasError: 'no', error: null });
            return;
        }

        if (status === 'error') {
            this._updateStateNameInput({ disabled: false, hasError: 'undefine', error: null });
            this._updateStateNumberInput({ disabled: false, hasError: 'undefine', error: null });
            return;
        }

        if (typeof data[ID.PF_INPUT_NAME_ID] !== 'undefined') {
            this._updateStateNameInput({ disabled: false, hasError: 'yes', error: data[ID.PF_INPUT_NAME_ID] });
        } else {
            this._updateStateNameInput({ disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.PF_INPUT_NUMBER_ID] !== 'undefined') {
            this._updateStateNumberInput({ disabled: false, hasError: 'yes', error: data[ID.PF_INPUT_NUMBER_ID] });
        } else {
            this._updateStateNumberInput({ disabled: false, hasError: 'undefined', error: null });
        }
    };

    _beforeCallSaveParallel = () => {
        this._updateStateSaveButton({ disabled: true, isLoading: true, title: 'TTL_TO_SAVE_IN_PROGRESS' });
        this._updateStateNameInput({ disabled: true });
        this._updateStateNumberInput({ disabled: true });
        this._updateStateShowInGroupCheckbox({ disabled: true });
    };

    _afterCallSaveParallel = (payload) => {
        this._showError(payload);

        this._updateStateShowInGroupCheckbox({disabled: false});
        this._updateStateSaveButton({
            disabled: false,
            title: 'TTL_TO_SAVE',
            isLoading: false,
        });
    };

    _callSaveParallel = async (payload) => {
        this._beforeCallSaveParallel();
        try {
            const resp = await fetcher('saveParallel', payload, 'api/v1');

            if (resp.status === 'ok') {
                openSiteURL('parallel-list.php');
            }

            this._afterCallSaveParallel({ status: resp.status, data: resp.data });
        } catch (e) {
            debugger;
            this._afterCallSaveParallel({ status: 'error' });
        }
    };

    _updateStateNameInput = (state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        this._stateNameInput = {
            disabled: disabled ?? this._stateNameInput.disabled,
            hasError: hasError ?? this._stateNameInput.hasError,
            error: error ?? this._stateNameInput.error,
        };

        if (disabled !== null) {
            this._atm.nameInput.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            this._atm.nameInput.updateProp('hasError', hasError);
        }
        if (error !== null && this._atm.nameInput.getProp('error') !== i18n(langId, error.code, error.args)) {
            this._atm.nameInput.updateProp('error', i18n(langId, error.code, error.args));
        }
    };

    _updateStateNumberInput = (state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        this._stateNumberInput = {
            disabled: disabled ?? this._stateNumberInput.disabled,
            hasError: hasError ?? this._stateNumberInput.hasError,
            error: error ?? this._stateNumberInput.error,
        };

        if (disabled !== null) {
            this._atm.numberInput.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            this._atm.numberInput.updateProp('hasError', hasError);
        }
        if (error !== null && this._atm.numberInput.getProp('error') !== i18n(langId, error.code, error.args)) {
            this._atm.numberInput.updateProp('error', i18n(langId, error.code, error.args));
        }
    };

    _updateStateSaveButton = (state) => {
        const { disabled = null, title = null, isLoading = null, icon = null } = state;
        const { langId } = this._prop;

        this._stateSaveButton = {
            disabled: disabled ?? this._stateSaveButton.disabled,
            title: title ?? this._stateSaveButton.title,
            isLoading: isLoading ?? this._stateSaveButton.isLoading,
            icon: icon ?? this._stateSaveButton.icon,
        };

        if (disabled !== null) {
            this._atm.saveButton.updateProp('disabled', disabled);
        }

        if (isLoading !== null) {
            this._atm.saveButton.updateProp('isLoading', isLoading);
        }

        if (title !== null) {
            this._atm.saveButton.updateProp('title', i18n(langId, title));
        }

        if (icon !== null) {
            this._atm.saveButton.updateProp('icon', icon);
        }
    };

    _updateStateShowInGroupCheckbox = (state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        this._stateShowInGroupCheckbox = {
            disabled: disabled ?? this._stateShowInGroupCheckbox.disabled,
            hasError: hasError ?? this._stateShowInGroupCheckbox.hasError,
            error: error ?? this._stateShowInGroupCheckbox.error,
        };

        if (disabled !== null) {
            this._atm.showInGroupCheckbox.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            this._atm.showInGroupCheckbox.updateProp('hasError', hasError);
        }
        if (error !== null && this._atm.showInGroupCheckbox.getProp('error') !== i18n(langId, error.code, error.args)) {
            this._atm.showInGroupCheckbox.updateProp('error', i18n(langId, error.code, error.args));
        }
    };

    _ui_render = () => {
        const { langId } = this._prop;

        return (
            <form class="mt-0 row gx-0 gy-3">
                <div class="bg-body-tertiary row border gy-3 m-0 pb-3">
                    {this._atm.nameInput}
                    {this._atm.numberInput}
                    {this._atm.showInGroupCheckbox}
                </div>
                <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                    {this._atm.saveButton}
                    <Button
                        className="btn btn-outline-secondary"
                        icon={'bi-x-circle'}
                        title={i18n(langId, 'TTL_TO_CANCEL')}
                        onClick={this._onCancelButtonClick}
                    />
                </div>
            </form>
        );
    };
}
