class Errors {
    constructor() {
        this.errors = {
            messages: [],
        };
    }

    record(error) {
        this.errors.messages.push(error);
    }

    createError() {
        for (let error of this.errors.messages) {
            for (let field in error) {
                console.log('field:' + field);
                if (error.hasOwnProperty(field)) {
                    const span = document.createElement('span');
                    span.innerText = error[field];
                    span.classList.add('form-error');
                    const input = document.querySelector(`input[name="${field}"]`);
                    console.log('input:' + input);
                    console.log('nextElmt:' + input.nextElementSibling);
                    if (!input.nextElementSibling.classList.contains('form-error')) {
                        input.parentNode.insertBefore(span, input.nextSibling);
                    }
                }
            }
        }
        this.errors.messages.length = 0;
    }
    
}

export default Errors;
