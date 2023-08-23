import { Api, ListStore } from '../lib';

export const api = new Api('/goods/categories');

export const listBindings = new ListStore(api);
