angular.module('app', 
	['ngAnimate', 'ngSanitize', 'ui.bootstrap', 'ngCookies', 'checklist-model', 'moment-picker', 'slick', 'ngMask', 
		'ngFileUpload', 'angular-web-notification', 'dndLists']);


/* Configuração do pace.js */
window.paceOptions = {
    document: true,
    eventLag: true,
    restartOnPushState: true,
    restartOnRequestAfter: true,
    ajax: {
        trackMethods: [ 'POST','GET', 'PUT', 'DELETE']
    }
};
