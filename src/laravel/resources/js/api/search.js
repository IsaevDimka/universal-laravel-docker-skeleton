import request from '@/utils/request';

export function userSearch(query) {
  return request({
    url: '/users',
    method: 'get',
    params: { query },
  });
}
