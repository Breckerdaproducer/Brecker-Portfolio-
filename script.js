const menu = document.querySelector(".menu");
const closeBtn = document.querySelector(".bx-x");
const openBtn = document.querySelector(".bx-dots-vertical");

openBtn.addEventListener("click", function(){
    menu.classList.add("active");
})
closeBtn.addEventListener("click", function(){
    menu.classList.remove("active");
})
