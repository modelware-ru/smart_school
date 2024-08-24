import { mount, el } from '../node_modules/redom/dist/redom.es';

import Notificator from './widget/notificator';
import StudentListHeader from './widget/studentListHeader/index';
import StudentListTable from './widget/studentListTable/index';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
const studentList = window.app.studentList;
PermissionManager.setPermissionList(window.app.permission);


mount(document.getElementById('main'), <StudentListTable langId={langId} studentList={studentList}/>);
mount(document.getElementById('student-list-header'), <StudentListHeader langId={langId} />);
mount(document.getElementById('main'), <Notificator langId={langId} show={false} fixed='top'/>);
