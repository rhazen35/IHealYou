import Ajax from "../ajax/ajax";
import Calendar from "./calendar";

/**
 * Appointment Module.
 */
class Appointment {

    /**
     * Constructor.
     */
    constructor() {

        this.inputFields = {'fullName': 0, 'email': 1, 'phone': 2, 'datetime': 3};
        this.ajax = new Ajax();
        this.events();
    }

    /**
     * Events.
     */
    events() {

        /**
         * Handle the for submit event.
         */
        document.getElementById('appointment-form').addEventListener('submit', (event) => {

            event.preventDefault();

            this.loader();

            let response = this.ajax.request(
                {
                    method: "POST",
                    payload: {
                        fullName: this.getValueFromId('new-appointment-fullName'),
                        email: this.getValueFromId('new-appointment-email'),
                        phone: this.getValueFromId('new-appointment-phone'),
                        datetime: this.getValueFromId('new-appointment-datetime')
                    },
                    url: "/appointment/new",
                    name: "new_appointment",
                    returnType: "json"
                }
            );

                response.then((data) => {

                this.removeErrors();
                this.loader(false);

               if ("failed" === data.type) {

                   this.addErrors(data.subjects);

               } else if("success" === data.type) {

                   new Calendar();
                   this.resetInputFields();
                   this.successCheckMark();
                   setTimeout(() => {
                       this.successCheckMark(false);
                   }, 3500);
               }
            });
        });

        for (let field in this.inputFields) {
            document.getElementById('new-appointment-' + field).addEventListener('change', (event) => {
                this.removeError(field);
            });
        }
    }

    getValueFromId(id) {
        return document.getElementById(id).value;
    }

    addErrors(subjects) {
        for (let subject in subjects) {
            document.getElementById('new-appointment-' + subject).classList.add('inputError');
            document.getElementById('new-appointment-' + subject + '-message').innerHTML = subjects[subject];
        }
    }

    resetInputFields() {
        for (let subject in this.inputFields) {
            document.getElementById('new-appointment-' + subject).value = "";
        }
    }

    removeError(field) {
        document.getElementById('new-appointment-' + field).classList.remove('inputError');
        document.getElementById('new-appointment-' + field + '-message').innerHTML = "";
    }

    removeErrors() {
        for (let subject in this.inputFields) {
            document.getElementById('new-appointment-' + subject).classList.remove('inputError');
            document.getElementById('new-appointment-' + subject + '-message').innerHTML = "";
        }
    }

    loader(enable = true) {
        document.getElementById('new-appointment-loader').style.display = false === enable ? "none" : "block";
    }

    successCheckMark(enable = true) {
        document.getElementById('new-appointment-success').style.display = false === enable ? "none" : "block";
    }
}

export default Appointment;
