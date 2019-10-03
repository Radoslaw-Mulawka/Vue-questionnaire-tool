import { login, logout, getInfo, register } from '@/api/auth';
import { getToken, setToken, removeToken } from '@/utils/auth';
import router, { resetRouter } from '@/router';
import store from '@/store';
import request from '@/utils/request';
import UserResource from '../../api/user';

const apiUser = new UserResource();

const state = {
  avatar: '',
  companyAddress: '',
  companyLogo: '',
  companyName: '',
  createdAt: '',
  email: '',
  firstName: '',
  id: null,
  lastName: '',

  token: getToken(),
  campaigns: '',
  // introduction: '',
  roles: [],
  permissions: [],
  usersListData: [],
};

const mutations = {
  SET_ID: (state, id) => {
    state.id = id;
  },
  SET_TOKEN: (state, token) => {
    state.token = token;
  },
  // SET_INTRODUCTION: (state, introduction) => {
  //   state.introduction = introduction;
  // },
  SET_NAME: (state, name) => {
    state.firstName = name;
  },
  SET_LAST_NAME: (state, name) => {
    state.lastName = name;
  },
  SET_AVATAR: (state, avatar) => {
    state.avatar = avatar;
  },
  SET_ROLES: (state, roles) => {
    state.roles = roles;
  },
  SET_PERMISSIONS: (state, permissions) => {
    state.permissions = permissions;
  },
  SET_EMAIL: (state, email) => {
    state.email = email;
  },
  SET_USERS_LIST: (state, usersListData) => {
    state.usersListData = usersListData;
  },
  SET_COMPANY_NAME: (state, companyName) => {
    state.companyName = companyName;
  },
  SET_COMPANY_ADDRESS: (state, companyAddress) => {
    state.companyAddress = companyAddress;
  },
  SET_COMPANY_LOGO: (state, companyLogo) => {
    async function imageImport() {
      const myImage = await import(`../../assets/login_page/town-login.png`);
      state.companyLogo = myImage['default'];
    }
    if (companyLogo === null) {
      imageImport();
    } else {
      state.companyLogo = companyLogo;
    }
  },
  SET_CREATED_AT: (state, createdAt) => {
    state.createdAt = createdAt;
  },
};

const actions = {
  getUsersList({ commit }) {
    request({
      url: '/users',
      method: 'get',
    })
      .then(response => {
        commit('SET_USERS_LIST', response.data);
      })
      .catch((error) => {
        console.error('Problem in getting users\' list data', error);
      });
  },
  // user login
  login({ commit }, userInfo) {
    const { email, password } = userInfo;
    return new Promise((resolve, reject) => {
      login({ email: email.trim(), password: password })
        .then(response => {
          commit('SET_TOKEN', response.token);
          setToken(response.token);
          resolve();
        })
        .catch(error => {
          reject(error);
        });
    });
  },

  // user registration
  register({ commit }, userInfo) {
    // const { email, password, accept_terms } = userInfo;
    return new Promise((resolve, reject) => {
      register(userInfo)
        .then(response => {
          // commit('SET_TOKEN', response.token);
          // setToken(response.token);
          resolve(response);
        })
        .catch(error => {
          reject(error);
        });
    });
  },

  // get user info
  getInfo({ commit, state }) {
    return new Promise((resolve, reject) => {
      getInfo(state.token)
        .then(response => {
          const { data } = response;

          if (!data) {
            reject('Verification failed, please Login again.');
          }

          // const { roles, name, avatar, introduction, permissions, id } = data;
          const { roles, firstName, lastName, avatar, email, permissions, id, companyAddress, companyLogo, companyName, createdAt } = data;
          // roles must be a non-empty array
          if (!roles || roles.length <= 0) {
            reject('getInfo: roles must be a non-null array!');
          }
          commit('SET_AVATAR', avatar);
          commit('SET_COMPANY_ADDRESS', companyAddress);
          commit('SET_COMPANY_LOGO', companyLogo);
          commit('SET_COMPANY_NAME', companyName);
          commit('SET_CREATED_AT', createdAt);
          commit('SET_EMAIL', email);
          commit('SET_NAME', firstName);
          commit('SET_ID', id);
          commit('SET_LAST_NAME', lastName);
          commit('SET_ROLES', roles);
          commit('SET_PERMISSIONS', permissions);
          // commit('SET_INTRODUCTION', introduction);

          resolve(data);
        })
        .catch(error => {
          reject(error);
        });
    });
  },

  // user logout
  logout({ commit, state }) {
    return new Promise((resolve, reject) => {
      logout(state.token)
        .then(() => {
          commit('SET_TOKEN', '');
          commit('SET_ROLES', []);
          removeToken();
          resetRouter();
          resolve();
        })
        .catch(error => {
          reject(error);
        });
    });
  },

  // remove token
  resetToken({ commit }) {
    return new Promise(resolve => {
      commit('SET_TOKEN', '');
      commit('SET_ROLES', []);
      removeToken();
      resolve();
    });
  },

  // Dynamically modify permissions
  changeRoles({ commit, dispatch }, role) {
    return new Promise(async resolve => {
      // const token = role + '-token';

      // commit('SET_TOKEN', token);
      // setToken(token);

      // const { roles } = await dispatch('getInfo');

      const roles = [role.name];
      const permissions = role.permissions.map(permission => permission.name);
      commit('SET_ROLES', roles);
      commit('SET_PERMISSIONS', permissions);
      resetRouter();

      // generate accessible routes map based on roles
      const accessRoutes = await store.dispatch('permission/generateRoutes', { roles, permissions });

      // dynamically add accessible routes
      router.addRoutes(accessRoutes);

      resolve();
    });
  },
  changeUserName({ commit }, value) {
    apiUser.updateProfile({
      firstName: value,
    }).then(() => {
      commit('SET_NAME', value);
    }).catch(error => {
      console.error(error);
    });
  },
  changeUserLastname({ commit }, value) {
    apiUser.updateProfile({
      lastName: value,
    }).then(() => {
      commit('SET_LAST_NAME', value);
    }).catch(error => {
      console.error(error);
    });
  },
  changeCompanyName({ commit }, value) {
    apiUser.updateProfile({
      companyName: value,
    }).then(() => {
      commit('SET_COMPANY_NAME', value);
    }).catch(error => {
      console.error(error);
    });
  },
  changeCompanyAddress({ commit }, value) {
    apiUser.updateProfile({
      companyAddress: value,
    }).then(() => {
      commit('SET_COMPANY_ADDRESS', value);
    }).catch(error => {
      console.error(error);
    });
  },
  changePassword({ commit }, passwordObject) {
    apiUser.changePassword(passwordObject).then(() => {
    }).catch(error => {
      console.error(error);
    });
  },
  changeCompanyBanner({ commit }, formData) {
    return new Promise((resolve, reject) => {
      apiUser.storeImage(formData).then((response) => {
        commit('SET_COMPANY_LOGO', response.data[0]);
        resolve();
      }).catch(error => {
        console.log(error);
        reject();
      });
    });
  },
  deleteCompanyLogo({ commit }) {
    apiUser.deleteImage().then(() => {
      commit('SET_COMPANY_LOGO', null);
    }).catch(e => {
      console.error(e);
    });
  },
  deleteProfile({ commit }) {
    apiUser.deleteProfile().then(() => {
      logout();
    }).catch(e => {
      console.error(e);
    });
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
