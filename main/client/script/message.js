import { mount, el } from '../node_modules/redom/dist/redom.es';

import Notificator from './widget/notificator';
import PermissionManager from './shared/permissionManager';

const { langId, message } = window.app;
PermissionManager.setPermissionList(window.app.permission);

mount(document.getElementById('main'), <Notificator langId={langId} show={true} message={message} />);
