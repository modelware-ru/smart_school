import { mount, el } from '../node_modules/redom/dist/redom.es';

import Notificator from './widget/notificator';
import Spinner from './widget/spinner';
import StudentGroupListHeader from './widget/studentGroupListHeader/index';
import StudentGroupListTable from './widget/studentGroupListTable/index';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
const studentList = window.app.studentList;
const lessonId = window.app.lessonId;
const groupId = window.app.groupId;
const schoolYearIdId = window.app.schoolYearId;
const serieList = window.app.serieList;
const attendanceDict = window.app.attendanceDict;

PermissionManager.setPermissionList(window.app.permission);

mount(document.getElementById('student-group-list-header'), <StudentGroupListHeader langId={langId} serieList={serieList} lessonId={lessonId} groupId={groupId} />);
mount(
    document.getElementById('main'),
    <StudentGroupListTable
        langId={langId}
        studentList={studentList}
        attendanceDict={attendanceDict}
        groupId={groupId}
        schoolYearId={schoolYearIdId}
    />
);
mount(document.getElementById('main'), <Notificator langId={langId} show={false} fixed="top" />);
mount(document.getElementById('main'), <Spinner langId={langId} show={false} />);
