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
                    const span = document.createElement('span');
                    span.innerText = error[field];
                    span.classList.add('form-error');
                    const input = document.querySelector(`input[name="${field}"]`);
                    if (!input.nextElementSibling.classList.contains('form-error')) {
                        input.parentNode.insertBefore(span, input.nextSibling);
                    }
                }
            }
        }
        // clear messages
        this.errors.messages.length = 0;
    }
    
}

export default Errors;
