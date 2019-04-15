
class Header {

    constructor() {

        this.events();
    }

    events() {

        let header = document.getElementById('header');

        window.addEventListener('scroll', (event) => {

           let position = window.scrollY;

            if (position > 96) {
                header.classList.add('header-moved');
            } else if (position < 96) {
                header.classList.remove('header-moved');
            }
        });
    }
}
export default Header;