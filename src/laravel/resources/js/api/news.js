import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/news',
    method: 'get',
    params: query,
  });
}

export function fetchNews(id) {
  return request({
    url: '/news/' + id,
    method: 'get',
  });
}

export function fetchPv(id) {
  return request({
    url: '/news/' + id + '/pageviews',
    method: 'get',
  });
}

export function createNews(data) {
  return request({
    url: '/news/create',
    method: 'post',
    data,
  });
}

export function updateNews(data) {
  return request({
    url: '/news/update',
    method: 'put',
    data,
  });
}
