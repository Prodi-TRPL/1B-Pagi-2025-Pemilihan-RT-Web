const popupmenuContainer = document.querySelector('.popupmenu-container');
const popupmenu = document.getElementById('popupmenu');
const popupexitContainer = document.querySelector('.popupexit-container');
const popupexit = document.getElementById('popupexit');
const semuadots = document.querySelectorAll('.dots');

semuadots.forEach(function (dots) {
    dots.addEventListener('click', () => {
        popupmenuContainer.classList.toggle('open');
        popupmenu.classList.toggle('open');
        document.getElementById("menu-type").innerText = "Edit Sesi";
        if (popupmenuContainer.classList.contains('open')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'auto';
        }
    });
});

const allCancel = document.querySelectorAll('.cancel');
const ok = document.getElementById('ok');

allCancel.forEach(function (cancel) {
    cancel.addEventListener('click', () => {
        popupexitContainer.classList.remove('open');
        popupexit.classList.remove('open');
        popupmenuContainer.classList.remove('open');
        popupmenu.classList.remove('open');
        document.body.style.overflow = 'auto';
    });
})

ok.addEventListener('click', (e) => {
    e.preventDefault();
    popupmenuContainer.classList.remove('open');
    popupmenu.classList.remove('open');
    document.body.style.overflow = 'auto';
});

popupmenuContainer.addEventListener('click', (e) => {
    if (e.target === popupmenuContainer) {
        popupmenuContainer.classList.remove('open');
        popupmenu.classList.remove('open');
        document.body.style.overflow = 'auto';
    }
});

const addBtn = document.getElementById('addBtn');
addBtn.addEventListener('click', function (e) {
    popupmenuContainer.classList.toggle('open');
    popupmenu.classList.toggle('open');
    document.getElementById("menu-type").innerText = "Tambah Sesi";
    if (popupmenuContainer.classList.contains('open')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'auto';
    }
    this.classList.add('clicked');
    setTimeout(() => {
        this.classList.remove('clicked');
    }, 300);
});

const exitBtn = document.getElementById('exit-menu');
exitBtn.addEventListener('click', function (e) {
    popupexitContainer.classList.toggle('open');
    popupexit.classList.toggle('open');
    if (popupexitContainer.classList.contains('open')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'auto';
    }
});