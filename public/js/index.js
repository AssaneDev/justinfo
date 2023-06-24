console.log("hello");
const headerMobileIcon = document.querySelector('.header-mobile-icon');
const headerNavMobile = document.querySelector('.header-nav-mobile');

headerMobileIcon.addEventListener('click',()=>{
    headerNavMobile.classList.toggle('show');
})