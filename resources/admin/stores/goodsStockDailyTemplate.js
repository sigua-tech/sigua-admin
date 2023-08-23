import { ListStore, Api } from '../lib';

export const api = new Api('/goods/stocks/daily_templates');

export const listBindings = new ListStore(api);
