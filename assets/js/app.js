/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

require('../scss/app.scss');

const ajax = require('./ajax/ajax');
new ajax.default();

const header = require('./layout/header');
new header.default();

const menu = require('./layout/menu');
new menu.default();

const appointment = require('./scheduler/appointment');
new appointment.default();
