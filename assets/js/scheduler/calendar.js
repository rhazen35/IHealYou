import Ajax from "../ajax/ajax";

class Calendar {

    constructor() {
        this.ajax = new Ajax();
        this.events();
        this.calendar();
    }

    events() {


    }

    calendar() {

        let calendar = document.getElementById('calendar');

        if (calendar) {
            let response = this.ajax.request(
                {
                    method: "GET",
                    payload: {},
                    url: "/calendar/month",
                    name: "calendar_month",
                    returnType: "json"
                }
            );

            response.then((html) => {

                calendar.innerHTML = html;

            }).then(() => {

                const light = document.getElementById('light')
                document.addEventListener('mousemove', e => {
                    light.style.top = e.pageY + "px"
                    light.style.left = e.pageX + "px"
                })
            });
        }
    }
}
export default Calendar;
