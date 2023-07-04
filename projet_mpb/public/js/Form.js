/* import Errors from './Errors.js';
import Age from './Age.js';

class Form {
    constructor() {
        this.error = new Errors();
        this.isValid = false;
        this.allowed = ['firstname', 'lastname', 'mail', 'birthday', 'address', 'city', 'zipCode', 'country', 'password', 'quantity'];
        //this.age = new Age();
        this.mail = '';
        this.firstname = '';
        this.lastname = '';
        this.birthday = '';
        this.address = '';
        this.city = '';
        this.zipCode = '';
        this.quantity = '';
        this._contact = {
            mail: '',
            firstname: '',
            lastname: '',
            birthday: '',
            address: '',
            city: '',
            zipCode: '',
            quantity: ''
        };
    }

    post(data) {
        localStorage.setItem('valid-contact', JSON.stringify(data));
    }

    get contact() {
        return this._contact;
    }

    validate(fields) {
        for (let field of fields) {
            if (!this.allowed.includes(field.name)) {
                return this.error.record(
                    `Le champ ${field.name} n'est pas valide`
                );
            }

            if (field.name === 'mail') {
                if (this.validateEmail(field.value)) {
                    this.isValid = true;
                    this._contact.mail = field.value;
                } else {
                    this.error.record({ mail: 'Email invalide' });
                }
            }

            /* if (field.name === 'birthday') {
                this.isValid = false;
                this.age.birthday = field.value;
                if (this.age.isValid) {
                    this.isValid = true;
                    this._contact.age = this.age.birthday;
                } else {
                    this.error.record({ birthday: 'Age invalide' });
                }
            } 

            if (field.name === 'firstname') {
                if (!field.value || field.value.length < 3 || field.value.length > 100) {
                    this.error.record({ firstname: 'Prénom invalide' });
                }
                this._contact.firstname = field.value;
            }

            if (field.name === 'lastname') {
                if (!field.value || field.value.length < 3 || field.value.length > 100) {
                    this.error.record({ lastname: 'Nom invalide' });
                }
                this._contact.lastname = field.value;
            }

            if (field.name === 'address') {
                if (!field.value || field.value.length < 3 || field.value.length > 200) {
                    this.error.record({ address: 'Adresse invalide' });
                }
                this._contact.address = field.value;
            }

            if (field.name === 'city') {
                if (!field.value || field.value.length < 3 || field.value.length > 200) {
                    this.error.record({ city: 'Adresse invalide' });
                }
                this._contact.city = field.value;
            }

            if (field.name === 'zipCode') {
                if (!field.value || field.value.length < 3 || field.value.length > 50) {
                    this.error.record({ zipCode: 'Code postal invalide' });
                }
                this._contact.zipCode = field.value;
            }

        }
        if (this.error.errors.messages.length > 0) {
            this.isValid = false;
        }

        return this.isValid;
    }

    createError() {
        return this.error.createError();
    }

    removeError() {
        if (this.nextElementSibling.classList.contains('form-error')) {
            this.nextElementSibling.remove();
        }
    }

    validateEmail(email) {
        // https://stackoverflow.com/questions/46155/how-to-validate-an-email-address-in-javascript
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
}

export default Form;
 */

import Errors from './Errors.js';
//import Age from './Age.js';

class Form {
    constructor() {
        this.error = new Errors();
        this.isValid = false;
        this.allowed = ['mail', 'firstname', 'lastname', 'birthday', 'address' ,'zipCode', 'city', 'country', 'password', 'quantity'];
        this.mail = '';
        this.firstname = '';
        this.lastname = '';
        this.birthday = '';
        this.address = '';
        this.zipCode = '';
        this.city = '';
        this.country = '';
        this.password = '';
        this.quantity = '';
        this._contact = {
            mail: '',
            firstname: '',
            lastname: '',
            birthday: '',
            address: '',
            zipCode: '',
            city: '',
            country: '',
            password: '',
            quantity: ''
        };
    }

    post(data) {
        localStorage.setItem('valid-contact', JSON.stringify(data));
    }

    get contact() {
        return this._contact;
    }

    validate(fields) {
        for (let field of fields) {
            if (!this.allowed.includes(field.name)) {
                return this.error.record(
                    `Le champ ${field.name} n'est pas valide`
                );
            }

            if (field.name === 'mail') {
                if (this.validateEmail(field.value)) {
                    this.isValid = true;
                    this._contact.mail = field.value;
                } else {
                    this.error.record({ mail: 'Email invalide' });
                }
            }
            
            if (field.name === 'firstname') {
                if (!field.value || field.value.length < 3) {
                    this.error.record({ firstname: 'firstname invalide' });
                }
                this._contact.firstname = field.value;
            }

            if (field.name === 'lastname') {
                if (!field.value || field.value.length < 3) {
                    this.error.record({ lastname: 'lastname invalide' });
                }
                this._contact.lastname = field.value;
            }

            if (field.name === 'birthday') {
                if (!field.value || field.value.length < 3) {
                    this.error.record({ birthday: 'birthday invalide' });
                }
                this._contact.birthday = field.value;
            }

            if (field.name === 'address') {
                if (!field.value || field.value.length < 3) {
                    this.error.record({ address: 'address invalide' });
                }
                this._contact.address = field.value;
            }

            if (field.name === 'zipCode') {
                if (!field.value || field.value.length < 3) {
                    this.error.record({ zipCode: 'zipCode invalide' });
                }
                this._contact.zipCode = field.value;
            }

            if (field.name === 'city') {
                if (!field.value || field.value.length < 3) {
                    this.error.record({ city: 'city invalide' });
                }
                this._contact.city = field.value;
            }

            if (field.name === 'country') {
                if (!field.value || field.value.length < 3) {
                    this.error.record({ country: 'country invalide' });
                }
                this._contact.country = field.value;
            }

            if (field.name === 'password') {
                if (!field.value || field.value.length < 8) {
                    this.error.record({ password: 'password invalide' });
                }
                this._contact.password = field.value;
            }

            if (field.name === 'quantity') {
                if (!field.value) {
                    this.error.record({ quantity: 'quantity invalide' });
                }
                this._contact.quantity = field.value;
            }

        }
        if (this.error.errors.messages.length > 0) {
            this.isValid = false;
        } else this.isValid = true;

        return this.isValid;
    }

    createError() {
        return this.error.createError();
    }

    removeError() {
        if (this.nextElementSibling.classList.contains('form-error')) {
            this.nextElementSibling.remove();
        }
    }

    validateEmail(email) {
        // https://stackoverflow.com/questions/46155/how-to-validate-an-email-address-in-javascript
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
}

export default Form;
