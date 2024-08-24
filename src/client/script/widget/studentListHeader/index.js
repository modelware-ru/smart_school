import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import i18n from '../../shared/i18n/index';
import Button from '../../atom/button';
import SearchInput from '../../widget/searchInput';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class StudentFormHeader {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId } = settings;

        this._prop = {
            langId,
        };

        this._state = {
            markedClassStudentList: [],
            markedGroupStudentList: [],
        };

        this._stateChangeClassButton = {};
        this._atm.changeClassButton = (
            <Button className="btn btn-outline-success position-relative" onClick={this._onChangeClassButtonClick} icon={'bi-pencil'} title={' '} />
        );
        this._updateStateChangeButton('class', {
            disabled: true,
            title: 'TTL_TO_CHANGE_CLASS',
        });

        this._stateChangeGroupButton = {};
        this._atm.changeGroupButton = (
            <Button className="btn btn-outline-success position-relative" onClick={this._onChangeGroupButtonClick} icon={'bi-pencil'} title={' '} />
        );
        this._updateStateChangeButton('group', {
            disabled: true,
            title: 'TTL_TO_CHANGE_GROUP',
        });

        commonEventManager.subscribe('changedMarkedClassStudentList', this._onChangedMarkedClassStudentList);
        commonEventManager.subscribe('changedMarkedGroupStudentList', this._onChangedMarkedGroupStudentList);

        this.el = this._ui_render();
    }

    _onChangeClassButtonClick = () => {
        const { markedClassStudentList } = this._state;
        const ids = 'ids=' + markedClassStudentList.join(',');
        openSiteURL(`student-list-change-class.php?${ids}`);
    };

    _onChangeGroupButtonClick = () => {
        const { markedGroupStudentList } = this._state;
        const ids = 'ids=' + markedGroupStudentList.join(',');
        openSiteURL(`student-list-change-group.php?${ids}`);
    };

    _onChangedMarkedClassStudentList = (data) => {
        this._state['markedClassStudentList'] = data;

        const l = data.length;
        if (l > 0) {
            this._updateStateChangeButton('class', { disabled: false, title: 'TTL_TO_CHANGE_CLASS', titleArgs: [l] });
        } else {
            this._updateStateChangeButton('class', { disabled: true, title: 'TTL_TO_CHANGE_CLASS' });
        }
    };

    _onChangedMarkedGroupStudentList = (data) => {
        this._state['markedGroupStudentList'] = data;

        const l = data.length;
        if (l > 0) {
            this._updateStateChangeButton('group', { disabled: false, title: 'TTL_TO_CHANGE_GROUP', titleArgs: [l] });
        } else {
            this._updateStateChangeButton('group', { disabled: true, title: 'TTL_TO_CHANGE_GROUP' });
        }
    };

    _updateStateChangeButton = (entity, state) => {
        const { disabled = null, title = null, titleArgs = [''] } = state;
        const { langId } = this._prop;

        let stateChangeButton;
        let changeButton;
        switch (entity) {
            case 'class':
                stateChangeButton = this._stateChangeClassButton;
                changeButton = this._atm.changeClassButton;
                break;
            case 'group':
                stateChangeButton = this._stateChangeGroupButton;
                changeButton = this._atm.changeGroupButton;
                break;
            default:
                return;
        }

        stateChangeButton['disable'] = disabled ?? stateChangeButton.disabled;
        stateChangeButton['title'] = title ?? stateChangeButton.title;
        stateChangeButton['titleArgs'] = titleArgs;

        if (disabled !== null) {
            changeButton.updateProp('disabled', disabled);
        }

        if (title !== null) {
            changeButton.updateProp('title', i18n(langId, title, titleArgs));
        }
    };

    _ui_render = () => {
        const { langId } = this._prop;

        return (
            <div className="d-flex flex-fill gap-1 gap-md-3">
                <SearchInput langId={langId} />
                {this._atm.changeClassButton}
                {this._atm.changeGroupButton}
            </div>
        );
    };
}
