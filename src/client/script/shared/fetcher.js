import { commonEventManager } from './eventManager';

const fetcher = async (resource, payload, api = 'api/v1') => {
  const body = JSON.stringify({
    resource,
    payload,
  });

  try {
    const response = await fetch(`${api}/request.php`, {
      method: 'POST',
      mode: 'cors',
      credentials: 'include',
      body,
    });

    const data = await response.json();

    if (!response.ok) {
      commonEventManager.dispatch('showMessage', data.data);
    }
    return data;
  } catch (e) {
    debugger;
    console.log(e);
    commonEventManager.dispatch('showMessage', {
      code: 'ERR_UNKNOWN',
    });
    throw e;
  }
};

export { fetcher };
