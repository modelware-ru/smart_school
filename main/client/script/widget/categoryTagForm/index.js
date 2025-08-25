import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import ID from './ids';
import i18n from '../../shared/i18n/index';
import Input from '../../atom/input';
import Button from '../../atom/button';
import Textarea from '../../atom/textarea';
import LabelList from '../../widget/labelList/index';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class CategoryTagForm {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, categoryTag } = settings;

        this._prop = {
            langId,
            categoryTagId: categoryTag.id,
            categoryTagList: categoryTag.tagList,
        };

        this._state = {};

        this._stateNameInput = {};
        this._atm.nameInput = (
            <Input className="col-12" label={i18n(langId, 'TTL_CATEGORYTAG_NAME')} value={categoryTag.name} mandatory maxLength={100} />
        );
        this._updateStateNameInput({
            disabled: false,
            hasError: 'unknown',
        });

        this._stateNewTagListTextarea = {};
        this._atm.newTagListTextarea = (
            <Textarea
                className="col-12"
                label={i18n(langId, categoryTag.id === 0 ? 'TTL_TAG_LIST' : 'TTL_NEW_TAG_LIST')}
                value={''}
                help={'В качестве разделителя названий тегов используйте запятую (,)'}
            />
        );
        this._updateStateNewTagListTextareaInput({
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

        if (categoryTag.id === 0) {
            this._el.tagList = null;
        } else {
            this._el.tagList = <LabelList className="col-12" labelList={categoryTag.tagList} title={'TTL_TAG_LIST'} />;
        }

        this.el = this._ui_render();
    }

    _onSaveButtonClick = () => {
        const {categoryTagId} = this._prop;
        const name = this._atm.nameInput.getState('value');
        const removedTagIdList = categoryTagId === 0 ? [] : this._el.tagList.getRemovedLabelIdList();
        const newTagListStr = this._atm.newTagListTextarea.getState('value');
        const newTagList = newTagListStr.split(',').reduce((curry, item) => {
            const tag = item.trim();
            if (tag.length > 0) {
                curry.push(tag);
            }
            return curry;
        }, []);

        const { hasError, data } = this._validateFormData(name);

        this._showError({ status: hasError ? 'fail' : 'ok', data });

        commonEventManager.dispatch('hideMessage');

        if (!hasError) {
            const { categoryTagId } = this._prop;

            this._callSaveCategoryTag({ id: categoryTagId, name, removedTagIdList, newTagList });
        }
    };

    _onCancelButtonClick = () => {
        history.back();
    };

    _validateFormData = (name) => {
        let data = {};
        let hasError = false;
        if (name.length === 0) {
            data[ID.CTF_INPUT_NAME_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }

        return { hasError, data };
    };

    _showError = ({ status, data }) => {
        if (status === 'ok') {
            this._updateStateNameInput({ disabled: false, hasError: 'no', error: null });
            return;
        }

        if (status === 'error') {
            this._updateStateNameInput({ disabled: false, hasError: 'undefine', error: null });
            return;
        }

        if (typeof data[ID.CTF_INPUT_NAME_ID] !== 'undefined') {
            this._updateStateNameInput({ disabled: false, hasError: 'yes', error: data[ID.CTF_INPUT_NAME_ID] });
        } else {
            this._updateStateNameInput({ disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.CTF_TEXTAREA_TAG_LIST_ID] !== 'undefined') {
            this._updateStateNewTagListTextareaInput({ disabled: false, hasError: 'yes', error: data[ID.CTF_TEXTAREA_TAG_LIST_ID] });
        } else {
            this._updateStateNewTagListTextareaInput({ disabled: false, hasError: 'undefined', error: null });
        }
    };

    _beforeCallSaveCategoryTag = () => {
        this._updateStateSaveButton({ disabled: true, isLoading: true, title: 'TTL_TO_SAVE_IN_PROGRESS' });
        this._updateStateNameInput({ disabled: true });
    };

    _afterCallSaveCategoryTag = (payload) => {
        this._showError(payload);

        this._updateStateSaveButton({
            disabled: false,
            title: 'TTL_TO_SAVE',
            isLoading: false,
        });
    };

    _callSaveCategoryTag = async (payload) => {
        this._beforeCallSaveCategoryTag();
        try {
            const resp = await fetcher('saveCategoryTag', payload);

            if (resp.status === 'ok') {
                openSiteURL('category-tag-list.php');
            }

            this._afterCallSaveCategoryTag({ status: resp.status, data: resp.data });
        } catch (e) {
            debugger;
            this._afterCallSaveCategoryTag({ status: 'error' });
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

    _updateStateNewTagListTextareaInput = (state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        this._stateNewTagListTextarea = {
            disabled: disabled ?? this._stateNewTagListTextarea.disabled,
            hasError: hasError ?? this._stateNewTagListTextarea.hasError,
            error: error ?? this._stateNewTagListTextarea.error,
        };

        if (disabled !== null) {
            this._atm.newTagListTextarea.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            this._atm.newTagListTextarea.updateProp('hasError', hasError);
        }
        if (error !== null && this._atm.newTagListTextarea.getProp('error') !== i18n(langId, error.code, error.args)) {
            this._atm.newTagListTextarea.updateProp('error', i18n(langId, error.code, error.args));
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

        return (
            <div className="mt-0 row gx-0 gy-3">
                <div className="bg-body-tertiary row border gy-3 m-0 pb-3">
                    {this._atm.nameInput}
                    {this._el.tagList}
                    {this._atm.newTagListTextarea}
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
