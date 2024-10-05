import { mount, el } from '../node_modules/redom/dist/redom.es';

import SignInForm from './widget/signInForm/index';
import Notificator from './widget/notificator';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
PermissionManager.setPermissionList(window.app.permission);

mount(
  document.getElementById('main'),
  <main className='d-flex flex-column p-4 border mx-auto w-100'>
    <SignInForm langId={langId} />
  </main>
);
mount(document.getElementById('main'), <Notificator langId={langId} show={false}/>);
