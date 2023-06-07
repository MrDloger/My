let originalBxOnCustomEvent = BX.onCustomEvent;

// Глобальный объект,
// в который будет собираться статистика
hardcoreBXOnEventFrontLog = {
  events: {},
};

BX.onCustomEvent = function (objOrEvent, eventIHope, eventParams, secureParams) {

  if (!objOrEvent) {
    objOrEvent = null;
  }

  if (!eventIHope) {
    eventIHope = null;
  }

  if (!eventParams) {
    eventParams = null;
  }

  if (!secureParams) {
    secureParams = null;
  }

  let info = {};
  let realEvent, realObj;

  if (typeof objOrEvent === 'string') {
    realEvent = objOrEvent;
  } else if (typeof eventIHope === 'string') {
    realEvent = eventIHope;
  }

  if (typeof objOrEvent === 'object') {
    realObj = objOrEvent;
  } else if (typeof eventIHope === 'object') {
    realObj = eventIHope;
  } else if (typeof eventParams === 'object') {
    realObj = eventParams;
  }

  let err = new Error();
  info.trace = err.stack;
  info.event = realEvent;
  info.obj = realObj;
  info.params = eventParams;
  info.arguments = arguments;

  // live! =)
  hardcoreBXFrontPrintOnEvent(info, true);

  // Регистрируем в глобальном объекте
  let eventNameList = realEvent.split(' ');
  eventNameList.forEach(function (evt) {
    if (!hardcoreBXOnEventFrontLog.events[evt]) {
      hardcoreBXOnEventFrontLog.events[evt] = [];
    }
    hardcoreBXOnEventFrontLog.events[evt].push(info);
  });

  // Пинаем оригинальный метод
  return originalBxOnCustomEvent.call(this, objOrEvent, eventIHope, eventParams, secureParams);
};

// Метод для поиска среди собранной статистики
// информации по названию события
hardcoreBXOnEventLookingByEvent = function (event) {
  for (let e in hardcoreBXOnEventFrontLog.events) {
    if (
      !hardcoreBXOnEventFrontLog.events.hasOwnProperty(e)
      || e !== event
    ) {
      continue;
    }

    for (let ins = 0; ins < hardcoreBXOnEventFrontLog.events[e].length; ins++) {
      hardcoreBXFrontPrintOnEvent(hardcoreBXOnEventFrontLog.events[e][ins]);
    }
  }
};

// Вывод в консоль
hardcoreBXFrontPrintOnEvent = function (info, live) {

  let localInfo = Object.assign({}, info);

  console.log(
    'BX.on%c%s',
    'background: #fa8544; color: #fff; ' +
    'font-weight: bold; padding: 3px 9px;' +
    'border-radius: 30px 0 0 30px;' +
    'border-left: 7px solid #1d1b57',
    localInfo.event
  );

  if (localInfo.obj) {
    console.log(localInfo.obj);
  }

  console.groupCollapsed('trace');
  if (live) {
    console.trace();
    delete (localInfo.trace);
  } else {
    console.log(localInfo.trace);
  }
  console.groupEnd();

  console.groupCollapsed('info');
  for (let i in localInfo) {
    if (localInfo.hasOwnProperty(i)) {
      console.log(i + ':%o', localInfo[i]);
    }
  }

  console.groupEnd();
};
