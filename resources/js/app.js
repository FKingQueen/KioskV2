// resources/js/app.js
const app = createApp(App)

require('./bootstrap');

import { createApp } from 'vue';
import App from './App.vue';
import Antd from 'ant-design-vue';
import router from './router/index.js';
import axios from 'axios';
import ViewUIPlus from 'view-ui-plus'
import 'view-ui-plus/dist/styles/viewuiplus.css';
import locale from 'view-ui-plus/dist/locale/en-US';

import Highcharts from "highcharts";
import StockModule from "highcharts/modules/stock";
import HighchartsVue from "highcharts-vue";

StockModule(Highcharts);


app.config.globalProperties.$axios = axios;
app.use(router)
app.use(HighchartsVue);
app.use(Antd)
app.use(ViewUIPlus, {locale: locale});
app.mount('#app');
