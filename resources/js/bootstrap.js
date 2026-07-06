// Axios — requêtes HTTP
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Bootstrap — composants UI
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;
