import { registerVueControllerComponents } from '@symfony/ux-vue';
import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

registerVueControllerComponents(require.context('./vue/controllers', true, /\.vue$/));


const ls = localStorage.getItem('theme');
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
const shouldDark = ls === 'dark' || (ls == null && prefersDark);
document.documentElement.classList.toggle('dark', shouldDark);
// Variante data-attr: document.documentElement.setAttribute('data-theme', shouldDark ? 'dark' : 'light');

