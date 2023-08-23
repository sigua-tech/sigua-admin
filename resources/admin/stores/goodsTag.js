import { ListStore, Api } from '../lib';

export const api = new Api('/goods/tags');

export const listBindings = new ListStore(api);
