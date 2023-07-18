const now = new Date();

const loader = document.createElement('div')
loader.classList.add('preloader')
loader.innerHTML = `<div class="lds-ring"><div></div><div></div><div></div><div></div></div>`
window.start_loader = function(){
    document.querySelectorAll('.preloader').forEach(el => { el.remove() })
    document.body.appendChild(loader)
}
window.end_loader = function(){
    document.querySelectorAll('.preloader').forEach(el => { el.remove() })
}
window.addEventListener("beforeunload", function(e){
    e.preventDefault()
    start_loader()
})
window.onload = function(e){
    e.preventDefault()
    document.getElementById('dt-year').innerHTML = now.getFullYear();
    end_loader()
}


