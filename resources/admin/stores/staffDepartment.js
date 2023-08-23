import { ListStore, Api } from '../lib';

export const api = new Api('/staffs/departments');

export const listBindings = new ListStore(api);
