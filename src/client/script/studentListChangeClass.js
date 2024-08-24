import { mount, el } from '../node_modules/redom/dist/redom.es';

import StudentChangeClassForm from './widget/studentChangeClassForm/index';
import Notificator from './widget/notificator';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
const studentIdList = window.app.studentIdList;
const parallelList = window.app.parallelList;

PermissionManager.setPermissionList(window.app.permission);

mount(
    document.getElementById('main'),
    <main className="d-flex flex-column">
        <StudentChangeClassForm langId={langId} parallelList={parallelList} studentIdList={studentIdList}/>
    </main>
);
mount(document.getElementById('main'), <Notificator langId={langId} show={false} />);
