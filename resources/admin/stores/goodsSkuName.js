import { ListStore, Api } from '../lib';

export const api = new Api('/goods/sku_names');

export const listBindings = new ListStore(api);
