//import { createApp } from 'vue';
import { createApp } from 'vue/dist/vue.esm-bundler';
import './bootstrap';


import ReportScreen from './pages/ReportScreen.vue';
import SchemeScreen from './pages/SchemeScreen.vue';
import UserScreen from './pages/UserScreen.vue';
import SwiftButton from './components/SwiftButton.vue';
import { helpers } from './helpers';

import TransactionSheet from './components/Scheme/TransactionSheet.vue';
import RibbonMenu from './components/Scheme/RibbonMenu.vue';
import NavTabs from './components/Scheme/NavTabs.vue';
import IndexScreen from './components/Scheme/IndexScreen.vue';
import AppropriationHistory from './components/Scheme/AppropriationHistory.vue';

import AddFundModal from './components/Scheme/Modals/AddFundModal.vue';
import DebitModal from './components/Scheme/Modals/DebitModal.vue';
import ExpenditureDetailsModal from './components/Scheme/Modals/ExpenditureDetailsModal.vue';

import SchemeModal from './components/Scheme/Modals/SchemeModal.vue';
import SharehoderModal from './components/Scheme/Modals/SharehoderModal.vue';
 

const app = createApp({});

app.config.globalProperties.$globals = helpers;
app.component('scheme-screen', SchemeScreen);
app.component('report-screen', ReportScreen);
app.component('user-screen', UserScreen);
//app.component('scheme-selector', schemeSelector); // Corrected camelCase component name
/* 
app.component('transaction-sheet', TransactionSheet);
app.component('ribbon-menu', RibbonMenu);
app.component('nav-tabs', NavTabs);
app.component('index-screen', IndexScreen);
app.component('appropriation-history', AppropriationHistory);
app.component('add-fund-modal', AddFundModal);
app.component('debit-modal', DebitModal);
app.component('expenditure-details-modal', ExpenditureDetailsModal);
app.component('projection-modal', ProjectionModal);
app.component('scheme-modal', SchemeModal);
app.component('shareholder-modal', ShareholderModal); // Corrected component name
*/
app.component('swift-button', SwiftButton); // Corrected component name

app.mount('#page-content');
 