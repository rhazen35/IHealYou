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

            });
        }
    }
}
export default Calendar;
