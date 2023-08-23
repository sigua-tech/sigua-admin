import { ListStore, Api } from '../lib';

export const api = new Api('/articles');

export const listBindings = new ListStore(api);
