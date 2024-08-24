import { mount, el } from '../node_modules/redom/dist/redom.es';

import StudentChangeGroupForm from './widget/studentChangeGroupForm/index';
import Notificator from './widget/notificator';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
const studentIdList = window.app.studentIdList;
const groupList = window.app.groupList;

PermissionManager.setPermissionList(window.app.permission);

mount(
    document.getElementById('main'),
    <main className="d-flex flex-column">
        <StudentChangeGroupForm langId={langId} groupList={groupList} studentIdList={studentIdList}/>
    </main>
);
mount(document.getElementById('main'), <Notificator langId={langId} show={false} />);
