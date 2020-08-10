import {login, logout, getInfo} from '@/api/auth';
import {getToken, setToken, removeToken} from '@/utils/auth';
import router, {resetRouter} from '@/router';
import store from '@/store';
import { Message } from 'element-ui';

const state = {
    id: null,
    user: null,
    token: getToken(),
    name: '',
    avatar: '',
    introduction: '',
    roles: [],
    permissions: [],
};

const mutations = {
    SET_ID: (state, id) => {
        state.id = id;
    },
    SET_TOKEN: (state, token) => {
        state.token = token;
    },
    SET_INTRODUCTION: (state, introduction) => {
        state.introduction = introduction;
    },
    SET_NAME: (state, name) => {
        state.name = name;
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
};

const actions = {
    // user login
    login({commit}, userInfo) {
        const {email, password} = userInfo;
        return new Promise((resolve, reject) => {
            login({email: email.trim(), password: password})
                .then(response => {
                    const {data} = response
                    commit('SET_TOKEN', data.token)
                    setToken(data.token)
                    resolve();
                })
                .catch(error => {
                    console.log(error);
                    reject(error);
                });
        });
    },

    // get user info
    getInfo({ commit, state }) {
        return new Promise((resolve, reject) => {
            getInfo(state.token).then(response => {
                const { data } = response

                if (!data) {
                    reject('Verification failed, please Login again.')
                }

                const { roles, name, avatar } = data

                // roles must be a non-empty array
                if (!roles || roles.length <= 0) {
                    reject('getInfo: roles must be a non-null array!')
                }

                const mapRoles = roles.map(role => role.name)
                commit('SET_ROLES', mapRoles)
                commit('SET_NAME', name)
                commit('SET_AVATAR', avatar)
                // commit('SET_INTRODUCTION', introduction)
                resolve(data)
            }).catch(error => {
                reject(error)
            })
        })
    },

    // user logout
    logout({commit}) {
        return new Promise((resolve, reject) => {
            logout()
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
    resetToken({commit}) {
        return new Promise(resolve => {
            commit('SET_TOKEN', '');
            commit('SET_ROLES', []);
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
