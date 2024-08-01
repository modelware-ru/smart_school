export default class Atom {
  // то, что может меняться только снаружи
  _prop = {};
  // то, что является внутренним состоянием и может меняться только изнутри
  // state часто получают начальные значения из prop
  _state = {};
  // функции обратного вызова
  // приходят в settings
  _callback = {};
  // Внутренние UI элементы
  _el = {};

  getState = (name) => {
    return this._state[name];
  };

  _updateState = (name, value, force = false) => {
    if (this._state[name] === value && !force) return;

    this._state[name] = value;
    this._renderState(name, value);
  };

  getProp = (name) => {
    return this._prop[name];
  };

  updateProp = (name, value, force = false) => {
    if (this._prop[name] === value && !force) return value;

    const prop = this._prop[name];
    this._prop[name] = value;
    this._renderProp(name, value);
    return prop;
  };

  updateCallback = (name, value) => {
    const callback = this._callback[name];
    this._callback[name] = value;
    return callback;
  };
}
