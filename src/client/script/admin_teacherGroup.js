import { mount, el } from '../node_modules/redom/dist/redom.es';

import Notificator from './widget/notificator';
import SelectMenu from './widget/selectMenu/index';
import Button from './atom/button';
import PermissionManager from './shared/permissionManager';

import i18n from './shared/i18n/index';
import { fetcher } from './shared/fetcher';

const langId = window.app.langId;
const teacherGroup = window.app.teacherGroup;
const teacherList = window.app.teacherList;
const schoolYearId = window.app.schoolYearId;

PermissionManager.setPermissionList(window.app.permission);

const selectMenuList = new Map();
const buttonList = new Map();

for (const [key, value] of Object.entries(teacherGroup)) {
    selectMenuList.set(
        key,
        <SelectMenu
            itemContent={teacherList}
            itemList={value}
            onChange={() => {
                const btn = buttonList.get(key);
                btn && btn.updateProp('disabled', false);
            }}
        />
    );
    buttonList.set(
        key,
        <Button
            className="btn btn-success"
            title={i18n(langId, 'TTL_TO_SAVE')}
            icon={'bi-floppy'}
            disabled={true}
            onClick={async () => {
                const select = selectMenuList.get(key);
                if (select) {
                    const teacherList = select.getState('itemList');

                    const btn = buttonList.get(key);
                    btn.updateProp('disabled', true);
                    btn.updateProp('isLoading', true);

                    const payload = {
                        groupId: parseInt(key),
                        schoolYearId,
                        teacherList,
                    };

                    try {
                        const resp = await fetcher('saveTeacherGroup', payload);

                        if (resp.status === 'ok') {
                        }

                        btn.updateProp('isLoading', false);
                    } catch (e) {
                        debugger;
                        btn.updateProp('disabled', false);
                        btn.updateProp('isLoading', false);
                    }
                }
            }}
        />
    );

    mount(
        document.getElementById(`gr_${key}`),
        <div className="d-flex flex-column">
            {selectMenuList.get(key)}
            <div className="d-flex">{buttonList.get(key)}</div>
        </div>
    );
}

mount(document.getElementById('main'), <Notificator langId={langId} show={false} fixed={'top'} />);
