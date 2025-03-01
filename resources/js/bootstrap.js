import axios from 'axios'

import '../css/app.css'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
}

const html = document.getElementById('main')
const darkMode = localStorage.getItem('sellexx_theme_type')
if (!darkMode)
    localStorage.setItem('sellexx_theme_type', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')

if (!localStorage.getItem('sellexx_direction'))
    localStorage.setItem('sellexx_direction', window.getComputedStyle ? window.getComputedStyle(html, null).getPropertyValue('direction') : html.currentStyle.direction)

html.classList.add(darkMode)
html.setAttribute('dir', localStorage.getItem('sellexx_direction'))
