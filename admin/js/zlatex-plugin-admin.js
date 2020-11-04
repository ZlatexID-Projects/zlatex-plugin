(function(  ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	const submitButton = document.querySelector(".old-events_page_generate-short-code #submit");
	submitButton.addEventListener("click",(e)=>{
		e.preventDefault();
		const importance = document.querySelector("input#importance").value;

		const postsNumber = document.querySelector("input#posts-number").value;

		const fromDate = document.querySelector("input#from-date").value;

		const toDate = document.querySelector("input#to-date").value;

		const shortCode = document.querySelector("input#shortcode");

		shortCode.value = `[events importance="${importance}"${postsNumber ? ` last="${postsNumber}"`:""}${fromDate ? ` fromdate="${fromDate}"` : ""}${toDate ? ` todate="${toDate}"` : ""}]`;

	})
})( );
