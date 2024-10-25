import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import ID from './ids';
import i18n from '../../shared/i18n/index';
import Input from '../../atom/input';
import Select from '../../atom/select';
import Button from '../../atom/button';
import SelectMenu from '../../widget/selectMenu/index';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class LessonForm {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, lesson, groupList, subjectList, serieList, serieListInLesson, date } = settings;

        this._prop = {
            langId,
            lessonId: lesson.id,
            callbackQuery: lesson.callbackQuery,
            groupList,
            subjectList,
            serieList,
            serieListInLesson,
            date,
        };

        this._state = {};

        this._stateDateInput = {};
        this._atm.dateInput = <Input type="date" className="col-12" label={i18n(langId, 'TTL_DATE')} value={lesson.date} mandatory />;

        this._updateStateDateInput({
            disabled: false,
            hasError: 'unknown',
        });

        this._stateGroupSelect = {};
        this._atm.groupSelect = (
            <Select className="col-12" label={i18n(langId, 'TTL_GROUP')} value={lesson.groupId} optionData={groupList} mandatory />
        );
        this._updateStateSelect(ID.LF_SELECT_GROUP_ID, {
            disabled: false,
            hasError: 'unknown',
        });

        this._stateSubjectSelect = {};
        this._atm.subjectSelect = (
            <Select className="col-12" label={i18n(langId, 'TTL_SUBJECT')} value={lesson.subjectId} optionData={subjectList} mandatory />
        );
        this._updateStateSelect(ID.LF_SELECT_SUBJECT_ID, {
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
        const date = this._atm.dateInput.getState('value');
        const groupId = parseInt(this._atm.groupSelect.getState('value'));
        const subjectId = parseInt(this._atm.subjectSelect.getState('value'));

        const { hasError, data } = this._validateFormData(date, groupId, subjectId);

        this._showError({ status: hasError ? 'fail' : 'ok', data });

        commonEventManager.dispatch('hideMessage');

        if (!hasError) {
            const { lessonId } = this._prop;
            const serieList = this._el.selectMenu.getState('itemList').map((item) => parseInt(item));

            this._callSaveLesson({ id: lessonId, date, groupId, subjectId, serieList });
        }
    };

    _onCancelButtonClick = () => {
        history.back();
    };

    _validateFormData = (lessonDate, groupId, subjectId) => {
        const {date} = this._prop;

        let data = {};
        let hasError = false;
        if (lessonDate.length === 0) {
            data[ID.LF_INPUT_DATE_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }

        const st = new Date(date.startDate).getTime();
        const ft = new Date(date.finishDate).getTime();
        const vt = new Date(lessonDate).getTime();
        if (vt > ft || vt < st) {
            data[ID.LF_INPUT_DATE_ID] = { code: 'MSG_FIELD_DATE_SHOULD_BE_BETWEEN', args: [date.startDate, date.finishDate] };
            hasError = true;
        }

        if (groupId === 0) {
            data[ID.LF_SELECT_GROUP_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }

        if (subjectId === 0) {
            data[ID.LF_SELECT_SUBJECT_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }

        return { hasError, data };
    };

    _showError = ({ status, data }) => {
        if (status === 'ok') {
            this._updateStateDateInput({ disabled: false, hasError: 'no', error: null });
            this._updateStateSelect(ID.LF_SELECT_GROUP_ID, { disabled: false, hasError: 'no', error: null });
            this._updateStateSelect(ID.LF_SELECT_SUBJECT_ID, { disabled: false, hasError: 'no', error: null });
            return;
        }

        if (status === 'error') {
            this._updateStateDateInput({ disabled: false, hasError: 'undefine', error: null });
            this._updateStateSelect(ID.LF_SELECT_GROUP_ID, { disabled: false, hasError: 'undefine', error: null });
            this._updateStateSelect(ID.LF_SELECT_SUBJECT_ID, { disabled: false, hasError: 'undefine', error: null });
            return;
        }

        if (typeof data[ID.LF_INPUT_DATE_ID] !== 'undefined') {
            this._updateStateDateInput({ disabled: false, hasError: 'yes', error: data[ID.LF_INPUT_DATE_ID] });
        } else {
            this._updateStateDateInput({ disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.LF_SELECT_GROUP_ID] !== 'undefined') {
            this._updateStateSelect(ID.LF_SELECT_GROUP_ID, { disabled: false, hasError: 'yes', error: data[ID.LF_SELECT_GROUP_ID] });
        } else {
            this._updateStateSelect(ID.LF_SELECT_GROUP_ID, { disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.LF_SELECT_SUBJECT_ID] !== 'undefined') {
            this._updateStateSelect(ID.LF_SELECT_SUBJECT_ID, { disabled: false, hasError: 'yes', error: data[ID.LF_SELECT_SUBJECT_ID] });
        } else {
            this._updateStateSelect(ID.LF_SELECT_SUBJECT_ID, { disabled: false, hasError: 'undefined', error: null });
        }
    };

    _beforeCallSaveLesson = () => {
        this._updateStateSaveButton({ disabled: true, isLoading: true, title: 'TTL_TO_SAVE_IN_PROGRESS' });
        this._updateStateDateInput({ disabled: true });
        this._updateStateSelect(ID.LF_SELECT_GROUP_ID, { disabled: true });
        this._updateStateSelect(ID.LF_SELECT_SUBJECT_ID, { disabled: true });
    };

    _afterCallSaveLesson = (payload) => {
        this._showError(payload);

        this._updateStateSaveButton({
            disabled: false,
            title: 'TTL_TO_SAVE',
            isLoading: false,
        });
    };

    _callSaveLesson = async (payload) => {
        this._beforeCallSaveLesson();
        try {
            const resp = await fetcher('saveLesson', payload);

            if (resp.status === 'ok') {
                const { callbackQuery } = this._prop;
                openSiteURL(`schedule.php?${callbackQuery}`);
            }

            this._afterCallSaveLesson({ status: resp.status, data: resp.data });
        } catch (e) {
            debugger;
            this._afterCallSaveLesson({ status: 'error' });
        }
    };

    _updateStateDateInput = (state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        this._stateDateInput = {
            disabled: disabled ?? this._stateDateInput.disabled,
            hasError: hasError ?? this._stateDateInput.hasError,
            error: error ?? this._stateDateInput.error,
        };

        if (disabled !== null) {
            this._atm.dateInput.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            this._atm.dateInput.updateProp('hasError', hasError);
        }
        if (error !== null && this._atm.dateInput.getProp('error') !== i18n(langId, error.code, error.args)) {
            this._atm.dateInput.updateProp('error', i18n(langId, error.code, error.args));
        }
    };

    _updateStateSelect = (entity, state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        let stateSelect;
        let nameSelect;
        switch (entity) {
            case ID.LF_SELECT_GROUP_ID:
                stateSelect = this._stateGroupSelect;
                nameSelect = this._atm.groupSelect;
                break;
            case ID.LF_SELECT_SUBJECT_ID:
                stateSelect = this._stateSubjectSelect;
                nameSelect = this._atm.subjectSelect;
                break;
            default:
                return;
        }

        stateSelect['disabled'] = disabled ?? stateSelect.disabled;
        stateSelect['hasError'] = hasError ?? stateSelect.hasError;
        stateSelect['error'] = error ?? stateSelect.error;

        if (disabled !== null) {
            nameSelect.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            nameSelect.updateProp('hasError', hasError);
        }
        if (error !== null && nameSelect.getProp('error') !== i18n(langId, error.code, error.args)) {
            nameSelect.updateProp('error', i18n(langId, error.code, error.args));
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
        const { serieList, serieListInLesson } = this._prop;

        return (
            <div className="mt-3 row gx-0 gy-3">
                <div className="bg-body-tertiary row border gy-3 m-0 pb-3">
                    {this._atm.dateInput}
                    {this._atm.groupSelect}
                    {this._atm.subjectSelect}
                    <hr />
                    <label className="form-label fw-bold my-0">{i18n(langId, 'TTL_LESSON_SERIES')}:</label>
                    {(this._el.selectMenu = <SelectMenu itemContent={serieList} itemList={serieListInLesson} />)}
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
            </div>
        );
    };
}
