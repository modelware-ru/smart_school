import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import i18n from '../../shared/i18n/index';
import Input from '../../atom/input';
import Select from '../../atom/select';
import Button from '../../atom/button';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class TeacherFormRemove {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, teacher, roleStateList, groupList, groupListForTeacher } = settings;

        this._prop = {
            langId,
            teacherId: teacher.id,
            roleStateList,
            groupList,
            groupListForTeacher,
        };

        this._state = {};

        this._atm.firstNameInput = <Input className="col-12" label={i18n(langId, 'TTL_FIRST_NAME')} value={teacher.firstName} disabled={true} />;
        this._atm.lastNameInput = <Input className="col-12" label={i18n(langId, 'TTL_LAST_NAME')} value={teacher.lastName} disabled={true} />;
        this._atm.middleNameInput = <Input className="col-12" label={i18n(langId, 'TTL_MIDDLE_NAME')} value={teacher.middleName} disabled={true} />;
        this._atm.roleStateSelect = (
            <Select className="col-12" label={i18n(langId, 'TTL_STATE')} value={teacher.roleStateId} optionData={roleStateList} disabled={true} />
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

        const { teacherId } = this._prop;

        this._callRemoveTeacher({ id: teacherId });
    };

    _onCancelButtonClick = () => {
        history.back();
    };

    _beforeCallRemoveTeacher = () => {
        this._updateStateRemoveButton({ disabled: true, isLoading: true, title: 'TTL_TO_REMOVE_IN_PROGRESS' });
    };

    _afterCallRemoveTeacher = (payload) => {
        this._updateStateRemoveButton({
            disabled: false,
            title: 'TTL_TO_REMOVE',
            isLoading: false,
        });
    };

    _callRemoveTeacher = async (payload) => {
        this._beforeCallRemoveTeacher();
        try {
            const resp = await fetcher('removeTeacher', payload);

            if (resp.status === 'ok') {
                openSiteURL('teacher-list.php');
            }

            this._afterCallRemoveTeacher({ status: resp.status, data: resp.data });
        } catch (e) {
            debugger;
            this._afterCallRemoveTeacher({ status: 'error' });
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
            <form className="mt-3 row gx-0 gy-3">
                <div className="bg-body-tertiary row border gy-3 m-0 pb-3">
                    {this._atm.firstNameInput}
                    {this._atm.lastNameInput}
                    {this._atm.middleNameInput}
                    {this._atm.roleStateSelect}
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
            </form>
        );
    };
}
