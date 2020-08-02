/**
 * Utils
 */
import Vue from 'vue'

import permission from './permission'
Vue.directive('permission', permission);

import role from './role'
Vue.directive('role', role);