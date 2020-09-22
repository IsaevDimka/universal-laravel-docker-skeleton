// set function parseTime,formatTime to filter
export { parseTime, formatTime } from '@/utils';

export function pluralize(time, label) {
  if (time === 1) {
    return time + label;
  }
  return time + label + 's';
}

export function timeAgo(time) {
  const between = Date.now() / 1000 - Number(time);
  if (between < 3600) {
    return pluralize(~~(between / 60), ' minute');
  } else if (between < 86400) {
    return pluralize(~~(between / 3600), ' hour');
  } else {
    return pluralize(~~(between / 86400), ' day');
  }
}

/* Number formating*/
export function numberFormatter(num, digits) {
  const si = [
    { value: 1E18, symbol: 'E' },
    { value: 1E15, symbol: 'P' },
    { value: 1E12, symbol: 'T' },
    { value: 1E9, symbol: 'G' },
    { value: 1E6, symbol: 'M' },
    { value: 1E3, symbol: 'k' },
  ];
  for (let i = 0; i < si.length; i++) {
    if (num >= si[i].value) {
      return (num / si[i].value + 0.1).toFixed(digits).replace(/\.0+$|(\.[0-9]*[1-9])0+$/, '$1') + si[i].symbol;
    }
  }
  return num.toString();
}

export function toThousandFilter(num) {
  return (+num || 0).toString().replace(/^-?\d+/g, m => m.replace(/(?=(?!\b)(\d{3})+$)/g, ','));
}

export function uppercaseFirst(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

export function truncate(text, limit, suffix) {
  text = text.trim();
  if( text.length <= limit) return text;
  text = text.slice( 0, limit); // тупо отрезать по лимиту
  let lastSpace = text.lastIndexOf(" ");
  if( lastSpace > 0) { // нашлась граница слов, ещё укорачиваем
    text = text.substr(0, lastSpace);
  }
  return text + suffix || '...';
}

export function toPercent(value) {
  return parseFloat(value) + ' %'
}

export function uppercaseFirstCamelCase(string = '') {
  if (!string) {
    return '';
  }
  return (string.charAt(0).toUpperCase() + string.slice(1)).replaceAll('_', ' ');
}