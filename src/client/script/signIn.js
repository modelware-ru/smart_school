import { mount, el } from '../node_modules/redom/dist/redom.es';

import ThemeSwitcher from './widget/themeSwitcher';
// import SignInForm from './widget/signInForm/index';
import Navigator from './widget/guestNavigator';
import Notificator from './widget/notificator';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
PermissionManager.setPermissionList(window.app.permission);

mount(document.body, <ThemeSwitcher langId={langId} />);
mount(document.getElementById('nav'), <Navigator langId={langId} resource={'signIn'} />);
mount(
  document.getElementById('main'),
  <main className='d-flex flex-column p-4 border mx-auto'>
    {/* <SignUpForm langId={langId} /> */}
  </main>
);
mount(document.getElementById('main'), <Notificator langId={langId} show={false}/>);
