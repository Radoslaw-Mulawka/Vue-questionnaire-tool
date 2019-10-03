import Cookies from 'js-cookie';
import { getLanguage } from '@/lang/index';
import request from '@/utils/request';

const state = {
  sidebar: {
    opened: Cookies.get('sidebarStatus') ? !!+Cookies.get('sidebarStatus') : true,
    withoutAnimation: false,
  },
  device: 'desktop',
  language: getLanguage(),
  size: Cookies.get('size') || 'medium',
  dashboard: null,
  campaignsListData: [],
};

const mutations = {
  TOGGLE_SIDEBAR: state => {
    state.sidebar.opened = !state.sidebar.opened;
    state.sidebar.withoutAnimation = false;
    if (state.sidebar.opened) {
      Cookies.set('sidebarStatus', 1);
    } else {
      Cookies.set('sidebarStatus', 0);
    }
  },
  CLOSE_SIDEBAR: (state, withoutAnimation) => {
    Cookies.set('sidebarStatus', 0);
    state.sidebar.opened = false;
    state.sidebar.withoutAnimation = withoutAnimation;
  },
  TOGGLE_DEVICE: (state, device) => {
    state.device = device;
  },
  SET_LANGUAGE: (state, language) => {
    state.language = language;
    Cookies.set('language', language);
  },
  SET_SIZE: (state, size) => {
    state.size = size;
    Cookies.set('size', size);
  },
  SET_DASHBOARD_INFO: (state, dashboardData) => {
    state.dashboard = dashboardData;
  },
};

const actions = {
  toggleSideBar({ commit }) {
    commit('TOGGLE_SIDEBAR');
  },
  closeSideBar({ commit }, { withoutAnimation }) {
    commit('CLOSE_SIDEBAR', withoutAnimation);
  },
  toggleDevice({ commit }, device) {
    commit('TOGGLE_DEVICE', device);
  },
  setLanguage({ commit }, language) {
    commit('SET_LANGUAGE', language);
  },
  setSize({ commit }, size) {
    commit('SET_SIZE', size);
  },
  getDashboardInfo({ commit }) {
    new Promise((resolve, reject) => {
      request({
        url: `/dashboard`,
        method: 'get',
      }).then(response => {
        commit('SET_DASHBOARD_INFO', response.data);
        resolve('dashboard\'s data has been gotten');
      }).catch((error) => {
        console.error('Problem in getting dashboard\' data', error);
        reject('Problem in getting dashboard\' data');
      });
    });
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
