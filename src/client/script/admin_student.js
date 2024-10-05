import { mount, el } from '../node_modules/redom/dist/redom.es';

import StudentForm from './widget/studentForm/index';
import StudentFormRemove from './widget/studentFormRemove/index';
import Notificator from './widget/notificator';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
const student = window.app.student;
const action = window.app.action;

PermissionManager.setPermissionList(window.app.permission);

mount(
    document.getElementById('main'),
    <main className="d-flex flex-column">
        {action === 'remove' ? <StudentFormRemove langId={langId} student={student} /> : <StudentForm langId={langId} student={student} />}
    </main>
);
mount(document.getElementById('main'), <Notificator langId={langId} show={false} />);
