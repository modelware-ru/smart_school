import { mount, el } from '../node_modules/redom/dist/redom.es';

import Notificator from './widget/notificator';
import SearchInput from './widget/searchInput';
import TeacherListTable from './widget/teacherListTable/index';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
const teacherList = window.app.teacherList;
PermissionManager.setPermissionList(window.app.permission);


mount(document.getElementById('main'), <TeacherListTable langId={langId} teacherList={teacherList}/>);
mount(document.getElementById('search-input'), <SearchInput langId={langId} />);
mount(document.getElementById('main'), <Notificator langId={langId} show={false} fixed='top'/>);
