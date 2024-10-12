import { mount, el } from '../node_modules/redom/dist/redom.es';

import TeacherForm from './widget/teacherForm/index';
import TeacherFormRemove from './widget/teacherFormRemove/index';
import Notificator from './widget/notificator';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
const teacher = window.app.teacher;
const roleStateList = window.app.roleStateList;
const action = window.app.action;

PermissionManager.setPermissionList(window.app.permission);

mount(
    document.getElementById('main'),
    <main className="d-flex flex-column">
        {action === 'remove' ? (
            <TeacherFormRemove langId={langId} teacher={teacher} roleStateList={roleStateList} />
        ) : (
            <TeacherForm langId={langId} teacher={teacher} roleStateList={roleStateList} />
        )}
    </main>
);
mount(document.getElementById('main'), <Notificator langId={langId} show={false} />);
