import { mount, el } from '../node_modules/redom/dist/redom.es';

import CategoryTagForm from './widget/categoryTagForm/index';
import CategoryTagFormRemove from './widget/categoryTagFormRemove/index';
import Notificator from './widget/notificator';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
const categoryTag = window.app.categoryTag;
const action = window.app.action;
PermissionManager.setPermissionList(window.app.permission);

mount(
    document.getElementById('main'),
    <main className="d-flex flex-column">
        {action === 'remove' ? <CategoryTagFormRemove langId={langId} categoryTag={categoryTag} /> : <CategoryTagForm langId={langId} categoryTag={categoryTag} />}
    </main>
);
mount(document.getElementById('main'), <Notificator langId={langId} show={false} />);
