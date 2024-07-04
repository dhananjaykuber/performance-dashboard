/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/css/admin.css":
/*!******************************!*\
  !*** ./assets/css/admin.css ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
/*!****************************!*\
  !*** ./assets/js/admin.js ***!
  \****************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _css_admin_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../css/admin.css */ "./assets/css/admin.css");

jQuery(document).ready(function ($) {
  if (typeof performanceData !== 'undefined') {
    const ctx = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: performanceData.labels,
        datasets: [{
          label: 'TTFB',
          data: performanceData.ttfb,
          borderColor: 'rgb(255, 99, 132)',
          fill: false
        }, {
          label: 'LCP',
          data: performanceData.lcp,
          borderColor: 'rgb(54, 162, 235)',
          fill: false
        }, {
          label: 'CLS',
          data: performanceData.cls,
          borderColor: 'rgb(255, 206, 86)',
          fill: false
        }, {
          label: 'INP',
          data: performanceData.inp,
          borderColor: 'rgb(75, 192, 192)',
          fill: false
        }]
      },
      options: {
        responsive: true,
        scales: {
          x: {
            display: true,
            title: {
              display: true,
              text: 'Date'
            }
          },
          y: {
            display: true,
            title: {
              display: true,
              text: 'Value'
            }
          }
        }
      }
    });
  }
});
/******/ })()
;
//# sourceMappingURL=admin.js.map