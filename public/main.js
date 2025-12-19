document.addEventListener('DOMContentLoaded', function () {
    eventListeners();
});

function eventListeners() {
    const mobileMenu = document.querySelector('.mobile-menu');
    
    mobileMenu.addEventListener('click', navegacionResponsive);
}


function navegacionResponsive() {
    const navegacion = document.querySelector('.navigation');
    const mobileMenu = document.querySelector('.mobile-menu');

    if(mobileMenu.classList.contains('activo')){
        mobileMenu.classList.remove('activo');
    } else {
        mobileMenu.classList.add('activo');
    }



    if(navegacion.classList.contains('mostrar')){
        navegacion.classList.remove('mostrar');
    } else {
        navegacion.classList.add('mostrar');
    }
}