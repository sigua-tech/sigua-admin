import { ListStore, Api } from '../lib';

export const api = new Api('/goods');

export const listBindings = new ListStore(api);
