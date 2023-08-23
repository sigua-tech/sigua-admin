import { ref } from 'vue';
import { ListStore, Api } from '../lib';

export const permissions = ref([]);
export const api = new Api('/staffs/roles');
export const listBindings = new ListStore(api);
