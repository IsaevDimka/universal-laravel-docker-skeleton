import {login, logout, getInfo, oauthCallback} from '@/api/auth';
import {getToken, setToken, removeToken} from '@/utils/auth';
import router, {resetRouter} from '@/router';
import store from '@/store';
import { Message } from 'element-ui';

const state = {
    data: [],
    token: getToken(),
    roles: [],
    permissions: [],
};

const mutations = {
    SET_USER: (state, user) => {
        state.data = user;
    },
    SET_TOKEN: (state, token) => {
        state.token = token;
    },
    SET_ROLES: (state, roles) => {
        state.roles = roles;
    },
    SET_PERMISSIONS: (state, permissions) => {
        state.permissions = permissions;
    },
};

const actions = {
    // user login
    login({commit}, userInfo) {
        const {email, password, phone, auth_type} = userInfo;
        return new Promise((resolve, reject) => {
            login({auth_type: auth_type, email: email.trim(), phone: phone.trim(), password: password})
                .then(response => {
                    const { data } = response;

                    setToken(data.data.token)
                    commit('SET_TOKEN', data.data.token)
                    resolve();
                })
                .catch(error => {
                    console.log(error);
                    reject(error);
                });
        });
    },
    oauthCallback({commit}, data)
    {
        const {driver, query } = data;
        return new Promise((resolve, reject) => {
            oauthCallback(driver, query)
                .then(response => {
                    const { token } = response.data.data;
                    setToken(token)
                    commit('SET_TOKEN', token)
                    resolve();
                })
                .catch(error => {
                    console.log(error);
                    reject(error);
                });
        });
    },
    getInfo({ commit, state }) {
        return new Promise((resolve, reject) => {
            getInfo(state.token).then(response => {
                const { data } = response

                if (!data) {
                    reject('Verification failed, please Login again.')
                }

                const user = data.data;

                // roles must be a non-empty array
                if (!user.roles || user.roles.length <= 0) {
                    reject('getInfo: roles must be a non-null array!');
                }

                commit('SET_USER', user)
                commit('SET_ROLES', user.roles)
                commit('SET_PERMISSIONS', user.permissions);
                resolve(data)
            }).catch(error => {
                reject(error);
            })
        })
    },

    // user logout
    logout({commit}) {
        return new Promise((resolve, reject) => {
            logout()
                .then(() => {
                    commit('SET_TOKEN', '');
                    commit('SET_USER', '')
                    commit('SET_ROLES', []);
                    commit('SET_PERMISSIONS', []);
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
    resetToken({commit}) {
        return new Promise(resolve => {
            commit('SET_TOKEN', '');
            commit('SET_USER', '')
            commit('SET_ROLES', []);
            commit('SET_PERMISSIONS', []);
            removeToken();
            resolve();
        });
    },

    // Dynamically modify permissions
    changeRoles({commit, dispatch}, role) {
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
            const accessRoutes = await store.dispatch('permission/generateRoutes', {roles, permissions});

            // dynamically add accessible routes
            router.addRoutes(accessRoutes);

            resolve();
        });
    },
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
};
