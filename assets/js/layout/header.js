/**
 * Header Module
 */
class Header {

    /**
     * Constructor
     */
    constructor() {

        // Launch the events
        this.events();
    }

    /**
     * Events
     */
    events() {

        let header = document.getElementById('header');

        // Add a scroll event on the header.
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