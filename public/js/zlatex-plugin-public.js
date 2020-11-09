let showed,lenght;
showed = 2;
let maxCOntent;
let step = 3;
let after;

class Post {
	constructor(id, title,postLink, imgLink, content){
		this.article = document.createElement("article");
		this.article.id = `post-${id}`;
		this.article.classList.add(`post-${id}`, 'old_events', 'type-old_events', 'status-publish', 'has-post-thumbnail', 'hentry');
		 
		//header
		this.headerArticle = document.createElement("header");
		this.headerArticle.classList.add("entry-header");
	
		//title
		this.headerTitle = document.createElement("h2");
		this.headerTitle.classList.add("entry-title");
	
		this.titleLink = document.createElement("a"); // link to  post
		this.titleLink.href = postLink;
		this.titleLink.rel = "bookmark";
		this.titleLink.textContent = title;	
	
	
		//end Header
		this.thumbnailDiv = document.createElement("div"); //post thumbnail
		this.thumbnailDiv.classList.add("post-thumbnail");

		this.thumnailLink = document.createElement("a");
		this.thumnailLink.href = postLink;

		this.thumbnail = document.createElement("img");
		this.thumbnail.width = this.thumbnail.height = 512;
		this.thumbnail.src = imgLink;	
		this.thumbnail.classList.add("attachment-twentyseventeen-featured-image", "size-twentyseventeen-featured-image", "wp-post-image");
		this.thumbnail.loading = "lazy";

		//end Thumbnail
		this.content = document.createElement("div");
		this.content.classList.add("entry-content");
		this.content.innerHTML = content;

		this.appentElements();
	}
	appentElements (){
		this.article.appendChild(this.headerArticle);
		this.article.appendChild(this.thumbnailDiv);
		this.article.appendChild(this.content);

		this.headerArticle.appendChild(this.headerTitle);
		this.headerTitle.appendChild(this.titleLink);

		this.thumbnailDiv.appendChild(this.thumnailLink);
		this.thumnailLink.appendChild(this.thumbnail);

	}
	getPost(){
		return this.article;
	}
	addPost(){
		document.querySelector(".site-main").appendChild(this.getPost());
	}
}

var graphFetch = (query) => {
	return new Promise (resolve => {
		fetch("/graphql", {
				method: "POST",
				headers: {
					'Content-Type' : 'application/json'
				},
				body: JSON.stringify(query)
		})
		.then(res=>{
			res.json().then(json=>{
				resolve(json);
			});
		});
	});
};	
// Save after and fet content length
(async function(){
	if (!postsInfo) return
	var q = {
		query : 
			`
			{
				events {
					edges {
						cursor
					  }
				}
			}
			`
	}
	var cursors = await graphFetch(q);
	lenght = cursors.data.events.edges.length;
	maxCOntent = cursors.data.events.edges.length;
	after = cursors.data.events.edges[postsInfo.showed - 1].cursor 
})();

document.addEventListener('DOMContentLoaded', ()=>{ 
	if(!postsInfo) return;
	showed = step = +postsInfo.showed;
	var loadMore = document.querySelector("#loadMore");
	console.log(lenght);
	if (!loadMore) return;
	if(lenght <= showed) loadMore.remove();
	// document.body.classList.add("has-sidebar","blog")
	loadMore.addEventListener("click",async (e)=>{		
		e.preventDefault();
		var query = {
			query : 
				`
				{
					events(first: ${step}, after: "${after}") {
						nodes {
							title
							databaseId
							uri
							featuredImage {
							  node {
								sourceUrl
							  }
							}
							excerpt
						  }
						  edges {
							cursor
						  }
					}
					
				}
				`
		}
		var posts = await graphFetch(query);
		var postslen = posts.data.events.edges.length;
		after = posts.data.events.edges[postslen-1].cursor
		posts = posts.data.events.nodes;
		showed = +showed + step;
		if(showed >= maxCOntent) loadMore.parentNode.remove();
		posts.forEach((el,i) => {
			new Post(el.databaseId,el.title,el.uri,el.featuredImage.node.sourceUrl,el.excerpt).addPost();
			if(i == posts.length -1 && showed < maxCOntent){
				document.querySelector(".site-main").appendChild(loadMore.parentNode);
			}
		});
	})

});
document.addEventListener('DOMContentLoaded', ()=>{
	document.querySelector(".serch-events-btn").addEventListener("click",async ()=>{
		const $searchText = document.querySelector(".serch-value");
		var radios = document.getElementsByName('importance');
		var importance = 1;
		for (let i = 0; i < radios.length; i++) {
			if (radios[i].checked) {
				// do whatever you want with the checked radio
				importance = radios[i].value;
				// only one radio can be logically checked, don't check the rest
				break;
			}
		}
		const $fromDate = document.querySelector("#from_date");
		const $toDate = document.querySelector("#to_date");

		const fromDate = new Date($fromDate.value).getTime() / 1000;
		const toDate = new Date($toDate.value).getTime() / 1000;

		const responce = await fetch("/wp-json/nice-plugin/v1/oldEvents-posts/",{
			method: "POST",
			headers: {
				'Content-Type' : 'application/json'
			},
			body: JSON.stringify({
				name: $searchText.value,
				importance: importance,
				to_date: toDate,
				from_date: fromDate
			})
		}
		);
		const posts = await responce.json();
		console.log(posts)
		const $main = document.querySelector("#main");
		$main.innerHTML = '';
		
		posts.forEach(el=>{
			const $mainDiv = document.createElement("div");
			$main.appendChild($mainDiv);

			const $title = document.createElement("a");
			$title.classList.add("title-serach-event");
			$title.textContent = el.title;
			$title.href = el.link;

			$mainDiv.appendChild($title);
			$mainDiv.appendChild(document.createElement("p"));

			const $postListText = document.createElement("p");
			$postListText.textContent = "Linked Posts";
			$mainDiv.appendChild($postListText);

			const $LinkedPosts = document.createElement("ul");
			el.posts.forEach(element=>{
				const $list = document.createElement("li");
				$list.textContent = element.post_title;
				$LinkedPosts.appendChild($list);
			})
			$mainDiv.appendChild($LinkedPosts);

			const $personsListText = document.createElement("p");
			$personsListText.textContent = "Persons";
			$mainDiv.appendChild($personsListText);

			const $LinkedPersons = document.createElement("ul");
			console.log(el.persons.name.length);
			for (let i = 0; i < el.persons.name.length; i++){
				const $list = document.createElement("li");
				console.log(el.persons.name[i]);
				$list.textContent = el.persons.name[i] + " " + el.persons.surname[i] + " " + el.persons.link[i];
				$LinkedPersons.appendChild($list);
			}
			
			$mainDiv.appendChild($LinkedPersons);

		})

	})
});
