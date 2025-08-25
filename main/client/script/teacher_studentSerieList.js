import { mount, el } from '../node_modules/redom/dist/redom.es';

import Notificator from './widget/notificator';
import Spinner from './widget/spinner';
import StudentSerieGroupListHeader from './widget/studentSerieGroupListHeader/index';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
const studentId = window.app.studentId;
const groupId = window.app.groupId;
const serieList = window.app.serieList;
const schoolYear = window.app.schoolYear;

PermissionManager.setPermissionList(window.app.permission);

mount(document.getElementById('student-serie-list-header'),
    <StudentSerieGroupListHeader langId={langId} serieList={serieList} studentId={studentId} groupId={groupId} schoolYear={schoolYear}/>);
mount(document.getElementById('main'), <Notificator langId={langId} show={false} fixed="top" />);
mount(document.getElementById('main'), <Spinner langId={langId} show={false} />);
