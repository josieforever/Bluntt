import './bootstrap';
import Search from './livesearch';

if (document.querySelector('.header-search-icon')) {
    new Search();
}