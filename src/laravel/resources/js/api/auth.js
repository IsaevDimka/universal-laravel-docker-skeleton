import request from '@/utils/request';

export function login(data) {
  return request({
    url: '/auth/login',
    method: 'post',
    data: data,
  });
}

export function getInfo(token) {
  return request({
    url: '/auth/user',
    method: 'get',
  });
}

export function logout() {
  return request({
    url: '/auth/logout',
    method: 'post',
  });
}

export function register(data) {
  return request({
    url: '/auth/register',
    method: 'post',
    data: data,
  });
}

export function oauthCallback(driver, query) {
  return request({
    url: `/oauth/${driver}/callback`,
    method: 'get',
    params: query,
  });
}