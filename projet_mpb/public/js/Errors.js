class Errors {
    constructor() {
        this.errors = {
            messages: [],
        };
    }

    // add error messages to the messages array
    record(error) {
        this.errors.messages.push(error);
    }

    //  display the error messages on the page
    createError() {
        for (let error of this.errors.messages) {
            for (let field in error) {
                if (error.hasOwnProperty(field)) {
                    // creation, display and stylisation of message errors
                    const span = document.createElement('span');
                    span.innerText = error[field];
                    span.classList.add('form-error');
                    const input = document.querySelector(`input[name="${field}"]`);
                    // insertion of span if doesn't exist yet
                    if (!input.nextElementSibling.classList.contains('form-error')) {
                        input.parentNode.insertBefore(span, input.nextSibling);
                    }
                }
            }
        }
        // clear error messages
        this.errors.messages.length = 0;
    }
    
}

export default Errors;
