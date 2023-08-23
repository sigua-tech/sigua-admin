import { Api, ListStore } from '../lib';

export const api = new Api('/articles/categories');

export const listBindings = new ListStore(api);
