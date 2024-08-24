import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import ID from './ids';
import i18n from '../../shared/i18n/index';
import Input from '../../atom/input';
import Select from '../../atom/select';
import Button from '../../atom/button';
import Textarea from '../../atom/textarea';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class StudentChangeClassForm {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, studentIdList, parallelList } = settings;

        this._prop = {
            langId,
            studentIdList,
            parallelList,
        };

        this._state = {};

        this._stateStartDateInput = {};
        this._atm.startDateInput = (
            <Input type="date" className="col-12" label={i18n(langId, 'TTL_DATE')} value={new Date().toISOString().substring(0, 10)} mandatory />
        );
        this._updateStateInput('startDate', {
            disabled: false,
            hasError: 'unknown',
        });

        this._stateParallelSelect = {};
        this._atm.parallelSelect = (
            <Select className="col-12" label={i18n(langId, 'TTL_PARALLEL')} value={'0'} optionData={parallelList} mandatory />
        );
        this._updateStateParallelSelect({
            disabled: false,
            hasError: 'unknown',
        });

        this._stateClassLetterInput = {};
        this._atm.classLetterInput = (
            <Input className="col-12" label={i18n(langId, 'TTL_CLASS_LETTER')} value={''} mandatory maxLength={100} />
        );
        this._updateStateInput('classLetter', {
            disabled: false,
            hasError: 'unknown',
        });

        this._stateReasonTextarea = {};
        this._atm.reasonTextarea = <Textarea className="col-12" label={i18n(langId, 'TTL_REASON')} value={''} />;
        this._updateStateReasonTextareaInput({
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
        const startDate = this._atm.startDateInput.getState('value');
        const parallelId = parseInt(this._atm.parallelSelect.getState('value'));
        const classLetter = this._atm.classLetterInput.getState('value');
        const reason = this._atm.reasonTextarea.getState('value');

        const { hasError, data } = this._validateFormData(startDate, parallelId, classLetter);

        this._showError({ status: hasError ? 'fail' : 'ok', data });

        commonEventManager.dispatch('hideMessage');

        if (!hasError) {
            const { studentIdList } = this._prop;

            this._callChangeClass({ startDate, parallelId, classLetter, reason, studentIdList });
        }
    };

    _onCancelButtonClick = () => {
        openSiteURL('student-list.php');

    };

    _validateFormData = (startDate, parallelId, classLetter) => {
        let data = {};
        let hasError = false;

        if (startDate.length === 0) {
            data[ID.CCF_INPUT_START_DATE_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }

        if (parallelId === 0) {
            data[ID.CCF_SELECT_PARALLEL_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }

        if (classLetter.length === 0) {
            data[ID.CCF_INPUT_CLASS_LETTER_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }

        return { hasError, data };
    };

    _showError = ({ status, data }) => {
        if (status === 'ok') {
            this._updateStateInput('startDate', { disabled: false, hasError: 'no', error: null });
            this._updateStateInput('classLetter', { disabled: false, hasError: 'no', error: null });
            this._updateStateParallelSelect({ disabled: false, hasError: 'no', error: null });
            return;
        }

        if (status === 'error') {
            this._updateStateInput('startDate', { disabled: false, hasError: 'undefine', error: null });
            this._updateStateInput('classLetter', { disabled: false, hasError: 'undefine', error: null });
            this._updateStateParallelSelect({ disabled: false, hasError: 'undefine', error: null });
            return;
        }

        if (typeof data[ID.CCF_INPUT_START_DATE_ID] !== 'undefined') {
            this._updateStateInput('startDate', { disabled: false, hasError: 'yes', error: data[ID.CCF_INPUT_START_DATE_ID] });
        } else {
            this._updateStateInput('startDate', { disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.CCF_INPUT_CLASS_LETTER_ID] !== 'undefined') {
            this._updateStateInput('classLetter', { disabled: false, hasError: 'yes', error: data[ID.CCF_INPUT_CLASS_LETTER_ID] });
        } else {
            this._updateStateInput('classLetter', { disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.CCF_SELECT_PARALLEL_ID] !== 'undefined') {
            this._updateStateParallelSelect({ disabled: false, hasError: 'yes', error: data[ID.CCF_SELECT_PARALLEL_ID] });
        } else {
            this._updateStateParallelSelect({ disabled: false, hasError: 'undefined', error: null });
        }
    };

    _beforeCallChangeClass = () => {
        this._updateStateSaveButton({ disabled: true, isLoading: true, title: 'TTL_TO_SAVE_IN_PROGRESS' });
        this._updateStateInput('startDate', { disabled: true });
        this._updateStateInput('className', { disabled: true });
        this._updateStateParallelSelect({ disabled: true });
    };

    _afterCallChangeClass = (payload) => {
        this._showError(payload);

        this._updateStateSaveButton({
            disabled: false,
            title: 'TTL_TO_SAVE',
            isLoading: false,
        });
    };

    _callChangeClass = async (payload) => {
        this._beforeCallChangeClass();
        try {
            const resp = await fetcher('changeClass', payload);

            if (resp.status === 'ok') {
                openSiteURL('student-list.php');
            }

            this._afterCallChangeClass({ status: resp.status, data: resp.data });
        } catch (e) {
            debugger;
            this._afterCallChangeClass({ status: 'error' });
        }
    };

    _updateStateInput = (entity, state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        let stateNameInput;
        let nameInput;
        switch (entity) {
            case 'startDate':
                stateNameInput = this._stateStartDateInput;
                nameInput = this._atm.startDateInput;
                break;
            case 'classLetter':
                stateNameInput = this._stateClassLetterInput;
                nameInput = this._atm.classLetterInput;
                break;
            default:
                return;
        }

        stateNameInput['disabled'] = disabled ?? stateNameInput.disabled;
        stateNameInput['hasError'] = hasError ?? stateNameInput.hasError;
        stateNameInput['error'] = error ?? stateNameInput.error;

        if (disabled !== null) {
            nameInput.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            nameInput.updateProp('hasError', hasError);
        }
        if (error !== null && nameInput.getProp('error') !== i18n(langId, error.code, error.args)) {
            nameInput.updateProp('error', i18n(langId, error.code, error.args));
        }
    };

    _updateStateParallelSelect = (state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        this._stateParallelSelect = {
            disabled: disabled ?? this._stateParallelSelect.disabled,
            hasError: hasError ?? this._stateParallelSelect.hasError,
            error: error ?? this._stateParallelSelect.error,
        };

        if (disabled !== null) {
            this._atm.parallelSelect.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            this._atm.parallelSelect.updateProp('hasError', hasError);
        }
        if (error !== null && this._atm.parallelSelect.getProp('error') !== i18n(langId, error.code, error.args)) {
            this._atm.parallelSelect.updateProp('error', i18n(langId, error.code, error.args));
        }
    };

    _updateStateReasonTextareaInput = (state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        this._stateReasonTextarea = {
            disabled: disabled ?? this._stateReasonTextarea.disabled,
            hasError: hasError ?? this._stateReasonTextarea.hasError,
            error: error ?? this._stateReasonTextarea.error,
        };

        if (disabled !== null) {
            this._atm.reasonTextarea.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            this._atm.reasonTextarea.updateProp('hasError', hasError);
        }
        if (error !== null && this._atm.reasonTextarea.getProp('error') !== i18n(langId, error.code, error.args)) {
            this._atm.reasonTextarea.updateProp('error', i18n(langId, error.code, error.args));
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

    _ui_render = () => {
        const { langId } = this._prop;
        const { activeTeacherList, teacherListInGroup } = this._prop;

        return (
            <form className="mt-3 row gx-0 gy-3">
                <div className="bg-body-tertiary row border gy-3 m-0 pb-3">
                    {this._atm.startDateInput}
                    {this._atm.parallelSelect}
                    {this._atm.classLetterInput}
                    {this._atm.reasonTextarea}
                </div>
                <div className="d-flex flex-wrap justify-content-between gap-2 mb-3">
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
