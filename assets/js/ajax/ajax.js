/**
 * Ajax Class.
 *
 * This class handles ajax requests.
 */
class Ajax {

    /**
     * Handle a request.
     */
    request(request) {

        // Set the method and build the url.
        let method = request.method;
        let url    = request.url;

        // Return a promise.
        return new Promise((resolve, reject) => {

            let options         = {};
            options.method      = method,
                options.credentials = "same-origin"

            if (method === "POST") {
                options.headers = {
                    "Content-Type": "application/json"
                };
                options.body= JSON.stringify({
                    "data": request.payload
                });
            }

            // Set the promise
            let promise = fetch(url, options).then((response) => {

                // Switch the return type.
                switch (request.returnType) {

                    // Handle the json return type.
                    case 'json':
                        return response.json();

                    // Handle the text return type.
                    case 'text':
                        return response.text();
                }
            }).then((data) => {
                return data.data;
            }).catch((error) => {
                console.log(error);
                return error;
            });

            // Resolve the promise.
            resolve(promise);
        });
    }
}

// Export the Ajax Module.
export default Ajax;