/**
 * Menu Module
 */
class Menu {

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

        let menu = document.getElementById('menu');
        let items = menu.getElementsByClassName('item');

        for (let i = 0; i < items.length; i++) {
            // Add a scroll event on the header.
            items[i].addEventListener('click', (event) => {

               let container = document.getElementById(event.target.id.replace('-link', ''));
               container.scrollIntoView({behavior: "smooth"});
            });
        }
    }
}
export default Menu;