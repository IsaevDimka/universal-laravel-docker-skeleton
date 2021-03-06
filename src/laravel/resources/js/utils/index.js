import { pluralize } from '@/filters';

export function parseTime(time, cFormat) {
  if (arguments.length === 0) {
    return null;
  }

  const format = cFormat || '{y}-{m}-{d} {h}:{i}:{s}';
  let date;
  if (typeof time === 'object') {
    date = time;
  } else {
    if (typeof time === 'string' && /^[0-9]+$/.test(time)) {
      time = parseInt(time) * 1000;
    }
    if (typeof time === 'number' && time.toString().length === 10) {
      time = time * 1000;
    }

    date = new Date(time);
  }
  const formatObj = {
    y: date.getFullYear(),
    m: date.getMonth() + 1,
    d: date.getDate(),
    h: date.getHours(),
    i: date.getMinutes(),
    s: date.getSeconds(),
    a: date.getDay(),
  };
  const timeStr = format.replace(/{(y|m|d|h|i|s|a)+}/g, (result, key) => {
    let value = formatObj[key];
    // Note: getDay() returns 0 on Sunday
    if (key === 'a') {
      return ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][value];
    }
    if (result.length > 0 && value < 10) {
      value = '0' + value;
    }

    return value || 0;
  });

  return timeStr;
}

export function formatTime(time, option) {
  time = +time * 1000;
  const d = new Date(time);
  const now = Date.now();

  const diff = (now - d) / 1000;

  if (diff < 30) {
    return 'Just now';
  } else if (diff < 3600) {
    // less 1 hour
    return pluralize(Math.ceil(diff / 60), ' minute') + ' ago';
  } else if (diff < 3600 * 24) {
    return pluralize(Math.ceil(diff / 3600), ' hour') + ' ago';
  } else if (diff < 3600 * 24 * 2) {
    return '1 day ago';
  }
  if (option) {
    return parseTime(time, option);
  } else {
    return (
      pluralize(d.getMonth() + 1, ' month') + ' ' +
      pluralize(d.getDate(), ' day') + ' ' +
      pluralize(d.getHours(), ' day') + ' ' +
      pluralize(d.getMinutes(), ' minute')
    );
  }
}

/**
 * Get query object from URL
 * @param {string} url
 */
export function getQueryObject(url) {
  url = url == null ? window.location.href : url;
  const search = url.substring(url.lastIndexOf('?') + 1);
  const obj = {};
  const reg = /([^?&=]+)=([^?&=]*)/g;
  search.replace(reg, (rs, $1, $2) => {
    const name = decodeURIComponent($1);
    let val = decodeURIComponent($2);
    val = String(val);
    obj[name] = val;
    return rs;
  });

  return obj;
}

/**
 * @param {string} input value
 * @returns {number} output value
 */
export function byteLength(str) {
  // returns the byte length of an utf8 string
  let s = str.length;
  for (var i = str.length - 1; i >= 0; i--) {
    const code = str.charCodeAt(i);
    if (code > 0x7f && code <= 0x7ff) {
      s++;
    } else if (code > 0x7ff && code <= 0xffff) {
      s += 2;
    }
    if (code >= 0xDC00 && code <= 0xDFFF) {
      i--;
    }
  }
  return s;
}

/**
 * Remove invalid (not equal true) elements from array
 *
 * @param {Array} actual
 */
export function cleanArray(actual) {
  const newArray = [];
  for (let i = 0; i < actual.length; i++) {
    if (actual[i]) {
      newArray.push(actual[i]);
    }
  }

  return newArray;
}

/**
 * Parse params from URL and return an object
 *
 * @param {string} url
 */
export function param2Obj(url) {
  const search = url.split('?')[1];
  if (!search) {
    return {};
  }
  return JSON.parse(
    '{"' +
    decodeURIComponent(search)
      .replace(/"/g, '\\"')
      .replace(/&/g, '","')
      .replace(/=/g, '":"')
      .replace(/\+/g, ' ') +
    '"}'
  );
}

/**
 * @param {string} val
 */
export function html2Text(val) {
  const div = document.createElement('div');
  div.innerHTML = val;

  return div.textContent || div.innerText;
}

/**
 * Merges two  objects, giving the last one precedence
 *
 * @param {Object} target
 * @param {Object} source
 */
export function objectMerge(target, source) {
  if (typeof target !== 'object') {
    target = {};
  }
  if (Array.isArray(source)) {
    return source.slice();
  }
  Object.keys(source).forEach(property => {
    const sourceProperty = source[property];
    if (typeof sourceProperty === 'object') {
      target[property] = objectMerge(target[property], sourceProperty);
    } else {
      target[property] = sourceProperty;
    }
  });

  return target;
}

/**
 * @param {HTMLElement} element
 * @param {string} className
 */
export function toggleClass(element, className) {
  if (!element || !className) {
    return;
  }
  let classString = element.className;
  const nameIndex = classString.indexOf(className);
  if (nameIndex === -1) {
    classString += '' + className;
  } else {
    classString =
      classString.substr(0, nameIndex) +
      classString.substr(nameIndex + className.length);
  }

  element.className = classString;
}

const DATETIME_FORMAT = 'YYYY-MM-DD H:mm:ss';

export const pickerOptions = {
  firstDayOfWeek: 1,
  shortcuts: [
    {
      text: 'Today',
      onClick(picker) {
        const start = moment().set({hours: 0, minutes: 0, seconds: 0}).format(DATETIME_FORMAT);
        const end = moment().set({hours: 23, minutes: 59, seconds: 59}).format(DATETIME_FORMAT);
        picker.$emit('pick', [start, end]);
      }
    },
    {
      text: 'Yesterday',
      onClick(picker) {
        const start = moment().subtract(1, 'days').set({
          hours: 0,
          minutes: 0,
          seconds: 0
        }).format(DATETIME_FORMAT);
        const end = moment().subtract(1, 'days').set({
          hours: 23,
          minutes: 59,
          seconds: 59
        }).format(DATETIME_FORMAT);
        picker.$emit('pick', [start, end]);
      }
    },
    {
      text: 'This week',
      onClick(picker) {
        const start = moment().startOf('isoWeek').set({
          hours: 0,
          minutes: 0,
          seconds: 0
        }).format(DATETIME_FORMAT);
        const end = moment().endOf('day').set({
          hours: 23,
          minutes: 59,
          seconds: 59
        }).format(DATETIME_FORMAT);
        picker.$emit('pick', [start, end]);
      }
    },
    {
      text: 'Previous week',
      onClick(picker) {
        const start = moment().subtract(1, 'weeks').startOf('isoWeek').set({
          hours: 0,
          minutes: 0,
          seconds: 0
        }).format(DATETIME_FORMAT);
        const end = moment().subtract(1, 'weeks').endOf('isoWeek').set({
          hours: 23,
          minutes: 59,
          seconds: 59
        }).format(DATETIME_FORMAT);
        picker.$emit('pick', [start, end]);
      }
    },
    {
      text: 'Last 30 Days',
      onClick(picker) {
        const start = moment().subtract(29, 'days').set({
          hours: 0,
          minutes: 0,
          seconds: 0
        }).format(DATETIME_FORMAT);
        const end = moment().set({hours: 23, minutes: 59, seconds: 59}).format(DATETIME_FORMAT);
        picker.$emit('pick', [start, end]);
      }
    },
    {
      text: 'This Month',
      onClick(picker) {
        const start = moment().startOf('month').set({
          hours: 0,
          minutes: 0,
          seconds: 0
        }).format(DATETIME_FORMAT);
        const end = moment().endOf('month').set({
          hours: 23,
          minutes: 59,
          seconds: 59
        }).format(DATETIME_FORMAT);
        picker.$emit('pick', [start, end]);
      }
    },
    {
      text: 'Last Month',
      onClick(picker) {
        const start = moment().subtract(1, 'month').startOf('month').set({
          hours: 0,
          minutes: 0,
          seconds: 0
        }).format(DATETIME_FORMAT);
        const end = moment().subtract(1, 'month').endOf('month').set({
          hours: 23,
          minutes: 59,
          seconds: 59
        }).format(DATETIME_FORMAT);
        picker.$emit('pick', [start, end]);
      }
    },
  ]
};

export function getTime(type) {
  if (type === 'start') {
    return new Date().getTime() - 3600 * 1000 * 24 * 90;
  } else {
    return new Date(new Date().toDateString());
  }
}

/**
 * @param {Function} func
 * @param {number} wait
 * @param {boolean} immediate
 */
export function debounce(func, wait, immediate) {
  let timeout, args, context, timestamp, result;

  const later = function() {
    // According to the last trigger interval
    const last = new Date().getTime() - timestamp;

    // The last time the wrapped function was called, the interval is last less than the set time interval wait
    if (last < wait && last > 0) {
      timeout = setTimeout(later, wait - last);
    } else {
      timeout = null;
      // If it is set to immediate===true, since the start boundary has already been called, there is no need to call it here.
      if (!immediate) {
        result = func.apply(context, args);
        if (!timeout) {
          context = args = null;
        }
      }
    }
  };

  return function(...args) {
    context = this;
    timestamp = new Date().getTime();
    const callNow = immediate && !timeout;
    // If the delay does not exist, reset the delay
    if (!timeout) {
      timeout = setTimeout(later, wait);
    }
    if (callNow) {
      result = func.apply(context, args);
      context = args = null;
    }

    return result;
  };
}

/**
 * This is just a simple version of deep copy
 * Has a lot of edge cases bug
 * If you want to use a perfect deep copy, use lodash's _.cloneDeep
 * @param {Object} source
 * @returns {Object}
 */
export function deepClone(source) {
  if (!source && typeof source !== 'object') {
    throw new Error('error arguments', 'deepClone');
  }
  const targetObj = source.constructor === Array ? [] : {};
  Object.keys(source).forEach(keys => {
    if (source[keys] && typeof source[keys] === 'object') {
      targetObj[keys] = deepClone(source[keys]);
    } else {
      targetObj[keys] = source[keys];
    }
  });

  return targetObj;
}

/**
 * @param {Object[]} arr
 * @returns {Object[]}
 */
export function uniqueArr(arr) {
  return Array.from(new Set(arr));
}

/**
 * @returns {string}
 */
export function createUniqueString() {
  const timestamp = +new Date() + '';
  const randomNum = parseInt((1 + Math.random()) * 65536) + '';
  return (+(randomNum + timestamp)).toString(32);
}

/**
 * Check if an element has a class
 *
 * @param {HTMLElement} elm
 * @param {String} cls
 */
export function hasClass(ele, cls) {
  return !!ele.className.match(new RegExp('(\\s|^)' + cls + '(\\s|$)'));
}

/**
 * Add class to element
 *
 * @param {HTMLElement} elm
 * @param {String} cls
 */
export function addClass(ele, cls) {
  if (!hasClass(ele, cls)) {
    ele.className += ' ' + cls;
  }
}

/**
 * Remove class from element
 *
 * @param {HTMLElement} elm
 * @param {String} cls
 */
export function removeClass(ele, cls) {
  if (hasClass(ele, cls)) {
    const reg = new RegExp('(\\s|^)' + cls + '(\\s|$)');
    ele.className = ele.className.replace(reg, ' ');
  }
}

export function sec2time(timeInSeconds) {
  const pad = (num, size) => ('000' + num).slice(size * -1)
  const time = parseFloat(timeInSeconds).toFixed(3)
  const hours = Math.floor(time / 60 / 60)
  const minutes = Math.floor(time / 60) % 60
  const seconds = Math.floor(time - minutes * 60)
  // const milliseconds = time.slice(-3)
  let str
  if (pad(hours, 2) > 0) {
    str = pad(hours, 2) + ':' + pad(minutes, 2) + ':' + pad(seconds, 2)
  } else {
    str = pad(minutes, 2) + ':' + pad(seconds, 2)
  }
  return str
}

export function uniqueArray(arr) {
  const result = []
  for (const str of arr) {
    if (!result.includes(str)) {
      result.push(str)
    }
  }
  return result
}