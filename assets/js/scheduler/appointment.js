import Ajax from "../ajax/ajax";

/**
 * Appointment Module.
 */
class Appointment {

    /**
     * Constructor.
     */
    constructor() {

        this.ajax = new Ajax();
        this.events();
    }

    /**
     * Events.
     */
    events() {

        document.getElementById('appointment-form').addEventListener('submit', (event) => {

            event.preventDefault();

            let fullName = document.getElementById('new-appointment-fullName').value;
            let email = document.getElementById('new-appointment-email').value;
            let datetime = document.getElementById('new-appointment-datetime').value;

            let payload = {fullName: fullName, email: email, datetime: datetime};
            let response = this.ajax.request(
                {
                    method: "POST",
                    payload: payload,
                    url: "/appointment/new",
                    name: "new_appointment",
                    returnType: "json"
                }
            );

            response.then((data) => {
               console.log(data);
            });
        });
    }
}

export default Appointment;