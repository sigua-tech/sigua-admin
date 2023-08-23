import { ListStore, Api } from '../lib';

export const api = new Api('/articles/tags');

export const listBindings = new ListStore(api);
