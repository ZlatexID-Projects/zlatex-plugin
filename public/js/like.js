// Like Functions

let liked = false;
var PostID = document.querySelector("#postID").value; // Отримання пост ід
function get_likes() {
    return new Promise (async (resolve)=>{
        var data = new FormData(); // Створення об'єкта для передачі в wordpress
        data.append("action", "get_likes"); 
        data.append("postID",document.querySelector("#postID").value);
        const response = await fetch(like.url, { 
            method: "POST",
            body: data,
        }); // Відправлення post запросу в wp.
       resolve(await response.json()); // Перетворення результату в json і поверння його.
    })  
}
function changeLike(whatDo){ // whatDo can be remove or add
    return new Promise (async (resolve)=>{
        var data = new FormData();
        data.append("action", `${whatDo}_likes`);
        data.append("postID",PostID);
        const response = await fetch(like.url, {
            method: "POST",
            body: data,
        });
       resolve(await response.json());
       // Якщо не лайкнуто, то добавити клас лайкнуто, інакше навпаки
       !liked ? document.querySelector(".like").classList.add("liked") : document.querySelector(".like").classList.remove("liked");
       // Якщо лайкнуто то записати в session storage інфу, що лайкнуто потст з id інакше змінти sessionStorage на те, що не лайкнуто
       !liked ? sessionStorage.setItem("liked" + PostID,"yes") : sessionStorage.setItem("liked" + PostID,"no");
       liked = !liked;
    })  
}
async function likeClick(event){
    event.preventDefault();
    const likeSpan = document.querySelector(".like a span"); // Кількість лайків
    likeSpan.textContent = liked ? +likeSpan.textContent - 1 : +likeSpan.textContent + 1; //Якщо лайкнуто, то відняти лайк, інакше добавити
    liked ? await changeLike("remove") : await changeLike("add"); //Якщо лайкнуто забрати лайк, інакше добавити
}

//End Like Functionst

(async function () {
    if (!"like" in window) return; // Якщо like не передалось з php, то просто вийти.
    var likes = await get_likes();
    document.querySelector(".like a span").textContent = likes;
    document.querySelector(".like a").addEventListener("click",likeClick);
    liked =  sessionStorage.getItem("liked" + PostID) == "yes" ? true : false;
    if(sessionStorage.getItem("liked" + PostID) == "yes") document.querySelector(".like").classList.add("liked");
})();
