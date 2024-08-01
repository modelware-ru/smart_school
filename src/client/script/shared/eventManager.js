class EventManager {
  _eventList = {};
  // hookList = {};

  constructor() {
    this._clear();
  }

  _clear = () => {
    this._eventList = {};
  };

  // addHook = (name, hook) => {
  //   if (typeof this.hookList[name] === 'undefined') {
  //     this.hookList[name] = [];
  //   }
  //   this.hookList[name].push(hook);
  // };

  // removeHook = (name, hook) => {
  //   if (typeof this.hookList[name] === 'undefined') return;

  //   const index = this.hookList[name].findIndex((item) => item === hook);
  //   if (index !== -1) this.hookList[name].splice(index, 1);
  // };

  subscribe = (name, listener) => {
    if (typeof this._eventList[name] === 'undefined') {
      this._eventList[name] = [];
    }
    this._eventList[name].push(listener);
  };

  dispatch = (name, args = {}) => {
    // сообщение уходит listeners только если все подключенные hook вернули true
    // if (this.hookList.hasOwnProperty(name)) {
    //   if (!this.hookList[name].every((hook) => hook(args))) return;
    // }

    if (this._eventList.hasOwnProperty(name)) {
      this._eventList[name].forEach((listener) => {
        listener(args);
      });
    }
  };
}

export let commonEventManager = new EventManager(); // singleton
export { EventManager }; // class
