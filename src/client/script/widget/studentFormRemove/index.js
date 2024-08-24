import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import i18n from '../../shared/i18n/index';
import Input from '../../atom/input';
import Button from '../../atom/button';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class StudentFormRemove {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, student } = settings;

        this._prop = {
            langId,
            studentId: student.id,
        };

        this._state = {};

        this._atm.loginInput = <Input className="col-12" label={i18n(langId, 'TTL_LOGIN')} value={student.login} disabled={true} />;
        this._atm.firstNameInput = <Input className="col-12" label={i18n(langId, 'TTL_FIRST_NAME')} value={student.firstName} disabled={true} />;
        this._atm.lastNameInput = <Input className="col-12" label={i18n(langId, 'TTL_LAST_NAME')} value={student.lastName} disabled={true} />;

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

        const { studentId } = this._prop;

        this._callRemoveStudent({ id: studentId });
    };

    _onCancelButtonClick = () => {
        openSiteURL('student-list.php');
    };

    _beforeCallRemoveStudent = () => {
        this._updateStateRemoveButton({ disabled: true, isLoading: true, title: 'TTL_TO_REMOVE_IN_PROGRESS' });
    };

    _afterCallRemoveStudent = (payload) => {
        this._updateStateRemoveButton({
            disabled: false,
            title: 'TTL_TO_REMOVE',
            isLoading: false,
        });
    };

    _callRemoveStudent = async (payload) => {
        this._beforeCallRemoveStudent();
        try {
            const resp = await fetcher('removeStudent', payload);

            if (resp.status === 'ok') {
                openSiteURL('student-list.php');
            }

            this._afterCallRemoveStudent({ status: resp.status, data: resp.data });
        } catch (e) {
            debugger;
            this._afterCallRemoveStudent({ status: 'error' });
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
