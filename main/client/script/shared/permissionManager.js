class PermissionManager {
  _permissionList = {};

  setPermissionList = (permissionList) => {
    this._permissionList = permissionList;
  };

  isAllowed = (widgetName, actionId) => {
    const widgetPermissionItem = this._getWidgetPermissionItem(widgetName, actionId);
    return widgetPermissionItem['permission'] === 'ALLOW';
  };

  getOptionList = (widgetName, actionId) => {
    const widgetPermissionItem = this._getWidgetPermissionItem(widgetName, actionId);
    return widgetPermissionItem['options'];
  };

  _getWidgetPermissionItem = (widgetName, actionId) => {
    const widgetItem = this._permissionList.hasOwnProperty(widgetName) ? this._permissionList[widgetName] : this._permissionList['*'];

    return widgetItem.hasOwnProperty(actionId) ? widgetItem[actionId] : widgetItem['*'];
  }
}

const pm = new PermissionManager(); // singleton
export default pm;
