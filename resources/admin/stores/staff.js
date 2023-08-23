import { ref } from 'vue';
import { ListStore, Api } from '../lib';

export const roles = ref([]);
export const departments = ref([]);

export const api = new Api('/staffs');
export const listBindings = new ListStore(api);
