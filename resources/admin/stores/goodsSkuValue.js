import { ListStore, Api } from '../lib';

export const api = new Api('/goods/sku_values');

export const listBindings = new ListStore(api);
