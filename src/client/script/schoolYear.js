import { mount, el } from '../node_modules/redom/dist/redom.es';

import SchoolYearForm from './widget/schoolYearForm/index';
import SchoolYearFormRemove from './widget/schoolYearFormRemove/index';
import Notificator from './widget/notificator';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
const schoolYear = window.app.schoolYear;
const action = window.app.action;
PermissionManager.setPermissionList(window.app.permission);

mount(
    document.getElementById('main'),
    <main className="d-flex flex-column">
        {action === 'remove' ? <SchoolYearFormRemove langId={langId} schoolYear={schoolYear} /> : <SchoolYearForm langId={langId} schoolYear={schoolYear} />}
    </main>
);
mount(document.getElementById('main'), <Notificator langId={langId} show={false} />);
