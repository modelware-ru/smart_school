import { mount, el } from '../node_modules/redom/dist/redom.es';

import GroupForm from './widget/groupForm/index';
import GroupFormRemove from './widget/groupFormRemove/index';
import Notificator from './widget/notificator';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
const group = window.app.group;
const parallelList = window.app.parallelList;
const activeTeacherList = window.app.activeTeacherList;
const teacherListInGroup = window.app.teacherListInGroup;
const action = window.app.action;
PermissionManager.setPermissionList(window.app.permission);

mount(
    document.getElementById('main'),
    <main className="d-flex flex-column">
        {action === 'remove' ? (
            <GroupFormRemove
                langId={langId}
                group={group}
                parallelList={parallelList}
                activeTeacherList={activeTeacherList}
                teacherListInGroup={teacherListInGroup}
            />
        ) : (
            <GroupForm langId={langId} group={group} parallelList={parallelList} activeTeacherList={activeTeacherList} teacherListInGroup={teacherListInGroup} />
        )}
    </main>
);
mount(document.getElementById('main'), <Notificator langId={langId} show={false} />);
