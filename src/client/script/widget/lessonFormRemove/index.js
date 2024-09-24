import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import i18n from '../../shared/i18n/index';
import Input from '../../atom/input';
import Select from '../../atom/select';
import Button from '../../atom/button';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class GroupFormRemove {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, lesson, groupList, subjectList } = settings;

        this._prop = {
            langId,
            lessonId: lesson.id,
            callbackQuery: lesson.callbackQuery,
            groupList,
        };

        this._state = {};

        this._atm.dateInput = <Input type="date" className="col-12" label={i18n(langId, 'TTL_DATE')} value={lesson.date} disabled={true} />;
        this._atm.groupSelect = (
            <Select className="col-12" label={i18n(langId, 'TTL_GROUP')} value={lesson.groupId} optionData={groupList} disabled={true} />
        );
        this._atm.subjectSelect = (
            <Select className="col-12" label={i18n(langId, 'TTL_SUBJECT')} value={lesson.subjectId} optionData={subjectList} disabled={true} />
        );
        this._stateRemoveButton = {};
        this._atm.removeButton = <Button className="btn btn-danger" onClick={this._onRemoveButtonClick} />;
        this._updateStateRemoveButton({
            disabled: false,
            title: 'TTL_TO_REMOVE',
            isLoading: false,
            icon: 'bi-trash3',
        });

        this.el = this._ui_render();
    }

    _onRemoveButtonClick = () => {
        commonEventManager.dispatch('hideMessage');

        const { lessonId } = this._prop;

        this._callRemoveLesson({ id: lessonId });
    };

    _onCancelButtonClick = () => {
        history.back();
    };

    _beforeCallRemoveLesson = () => {
        this._updateStateRemoveButton({ disabled: true, isLoading: true, title: 'TTL_TO_REMOVE_IN_PROGRESS' });
    };

    _afterCallRemoveLesson = (payload) => {
        this._updateStateRemoveButton({
            disabled: false,
            title: 'TTL_TO_REMOVE',
            isLoading: false,
        });
    };

    _callRemoveLesson = async (payload) => {
        this._beforeCallRemoveLesson();
        try {
            const resp = await fetcher('removeLesson', payload);

            if (resp.status === 'ok') {
                const { callbackQuery } = this._prop;
                openSiteURL(`schedule.php?${callbackQuery}`);
            }

            this._afterCallRemoveLesson({ status: resp.status, data: resp.data });
        } catch (e) {
            debugger;
            this._afterCallRemoveLesson({ status: 'error' });
        }
    };

    _updateStateRemoveButton = (state) => {
        const { disabled = null, title = null, isLoading = null, icon = null } = state;
        const { langId } = this._prop;

        this._stateRemoveButton = {
            disabled: disabled ?? this._stateRemoveButton.disabled,
            title: title ?? this._stateRemoveButton.title,
            isLoading: isLoading ?? this._stateRemoveButton.isLoading,
            icon: icon ?? this._stateRemoveButton.icon,
        };

        if (disabled !== null) {
            this._atm.removeButton.updateProp('disabled', disabled);
        }

        if (isLoading !== null) {
            this._atm.removeButton.updateProp('isLoading', isLoading);
        }

        if (title !== null) {
            this._atm.removeButton.updateProp('title', i18n(langId, title));
        }

        if (icon !== null) {
            this._atm.removeButton.updateProp('icon', icon);
        }
    };

    _ui_render = () => {
        const { langId } = this._prop;

        return (
            <div className="mt-3 row gx-0 gy-3">
                <div className="bg-body-tertiary row border gy-3 m-0 pb-3">
                    {this._atm.dateInput}
                    {this._atm.groupSelect}
                    {this._atm.subjectSelect}
                </div>
                <div className="d-flex flex-wrap justify-content-between gap-2 mb-3">
                    {this._atm.removeButton}
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
