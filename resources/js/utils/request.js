import axios from 'axios';
import { Message } from 'element-ui';
import { getToken, setToken } from '@/utils/auth';
import NProgress from 'nprogress';

// Create axios instance
const service = axios.create({
  baseURL: process.env.MIX_BASE_API,
  timeout: 10000, // Request timeout
});

// Request intercepter
service.interceptors.request.use(
  config => {
    NProgress.start();
    const token = getToken();
    if (token) {
      config.headers['Authorization'] = 'Bearer ' + getToken(); // Set JWT token
    }
    return config;
  },
  error => {
    // Do something with request error
    NProgress.done();
    console.log(error); // for debug
    Promise.reject(error);
  }
);

// response pre-processing
service.interceptors.response.use(
  response => {
    const urlsToCheck = ['campaigns', 'profile', 'user', 'questions', 'options'];
    NProgress.done();
    if (response.headers.authorization) {
      setToken(response.headers.authorization);
      response.data.token = response.headers.authorization;
    }
    for (let i = 0; i <= urlsToCheck.length; i++) {
      if (response.config.url.includes(urlsToCheck[i]) && response.config.method !== 'get') {
        console.log(response);
        Message({
          duration: 5 * 1000,
          message: response.data.message,
          type: 'success',
          showClose: true,
        });
      }
    }
    return response.data;
  },
  error => {
    NProgress.done();
    let message = error.message;
    if (error.response.data && error.response.data.status === 'error') {
      message = error.response.data.message;
    } else if (error.response.data && error.response.data.error) {
      message = error.response.data.error;
    }

    Message({
      duration: 5 * 1000,
      message: message,
      type: 'error',
      showClose: true,
    });
    return Promise.reject(error);
  },
);

export default service;
