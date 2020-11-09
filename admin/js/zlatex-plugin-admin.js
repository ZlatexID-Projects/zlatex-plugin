(async function(  ) {
	'use strict';
	var persons;
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

	const postAdder = new Choices('.post-search',{
		removeItemButton: true,
	});
	postAdder.passedElement.element.addEventListener('addItem', function (e){
		var d = new FormData();
		d.append("action", "add_post_to_event");
		d.append("id",localize.post_id);
		d.append("post_id",e.detail.value);
		d.append("post_title",e.detail.label);
		fetch(localize.url, {
			method: "POST",
			body: d,
		});
		console.log();
		// console.log(e.detail.value);
	});
	postAdder.passedElement.element.addEventListener('removeItem', function (e){
		var d = new FormData();
		d.append("action", "remove_post_from_event");
		d.append("id",localize.post_id);
		d.append("post_id",e.detail.value);
		fetch(localize.url, {
			method: "POST",
			body: d,
		});
		console.log();
		// console.log(e.detail.value);
	});
	const submitButton = document.querySelector(".old-events_page_generate-short-code #submit");
	if (submitButton) submitButton.addEventListener("click",(e)=>{
		e.preventDefault();
		const importance = document.querySelector("input#importance").value;

		const postsNumber = document.querySelector("input#posts-number").value;

		const fromDate = document.querySelector("input#from-date").value;

		const toDate = document.querySelector("input#to-date").value;

		const shortCode = document.querySelector("input#shortcode");

		shortCode.value = `[events importance="${importance}"${postsNumber ? ` last="${postsNumber}"`:""}${fromDate ? ` fromdate="${fromDate}"` : ""}${toDate ? ` todate="${toDate}"` : ""}]`;

	})

	
	const $addInput = document.querySelector(".button-add-input");
	var $inputsDivList;

	if($addInput){
		$inputsDivList = document.querySelector("#important_persons .inputs-list");
		$addInput.addEventListener("click",(e)=>{
			e.preventDefault();
			if(!$inputsDivList) return;
			new PersonInputBox().appendToElement($inputsDivList);
		});
		var d = new FormData();
		d.append("action", "get_persons");
		d.append("id",localize.post_id);

		const response = await fetch(localize.url, {
			method: "POST",
			body: d,
		});
		persons = await response.json();
		if(!$inputsDivList) return;
		if(!persons) return
		for (let i = 0; i < persons.name.length; i++ ){
			new PersonInputBox(persons.name[i],persons.surname[i],persons.link[i]).appendToElement($inputsDivList);
		}
	} 
	
	
	
	// var d = new FormData();
	// d.append("name", "h");
	// d.append("importance",3);
	// console.log(await fetch("/wp-json/nice-plugin/v1/oldEvents-posts/",{
	// 	method: "POST",
	// 	body:{
	// 		name: "h",
	// 		importance : 3
	// 	}
	// }
	
})( );
class PersonInputBox {
	#node = document.createElement("div");
	constructor(name, surname,link){
		this.#node.classList.add("input-block");

		const $phpdlyadaunov = document.createElement("input"); // name 
		$phpdlyadaunov.placeholder = "Name";
		$phpdlyadaunov.name = "name[]";
		$phpdlyadaunov.required = true;
		this.#node.appendChild($phpdlyadaunov);
		
		const $surName = document.createElement("input");
		$surName.placeholder = "Surname";
		$surName.name = "surname[]";
		this.#node.appendChild($surName);

		const $link = document.createElement("input");
		$link.placeholder = "Social Link";
		$link.name = "link[]";
		this.#node.appendChild($link);

		const $deleteBttn = document.createElement("button");
		$deleteBttn.textContent = "delete";
		$deleteBttn.classList.add("button");
		this.#node.appendChild($deleteBttn);

		$surName.type = $link.type = $phpdlyadaunov.type = "text";

		if(name) $phpdlyadaunov.value = name;
		if(surname) $surName.value = surname;
		if(link) $link.value = link;

		$deleteBttn.addEventListener("click",() => this.#node.remove());
	}
	appendToElement(el){
		el.appendChild(this.#node);
	}
}
