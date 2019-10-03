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

export function register(data) {
  return request({
    url: '/auth/register',
    method: 'post',
    data: {
      email: data.email,
      password: data.password,
      'password_confirmation': data['password_confirmation'],
      'accept_terms': data['accept_terms'],
    },
  });
}

export function logout() {
  return request({
    url: '/auth/logout',
    method: 'post',
  });
}
