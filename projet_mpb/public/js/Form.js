import Errors from './Errors.js';
//import Age from './Age.js';

class Form {
    constructor() {
        this.error = new Errors();
        this.isValid = false;
        this.allowed = ['token', 'mail', 'firstname', 'lastname', 'birthday', 'address' ,'zipCode', 'city', 'country', 'password', 'currentPassword', 'newPassword', 'newPasswordConfirm', 'quantity'];
        this.token = '';
        this.mail = '';
        this.firstname = '';
        this.lastname = '';
        this.birthday = '';
        this.address = '';
        this.zipCode = '';
        this.city = '';
        this.country = '';
        this.password = '';
        this.currentPassword = '';
        this.newPassword = '';
        this.newPasswordConfirm = '';
        this.quantity = '';
        this._contact = {
            //token: '',
            mail: '',
            firstname: '',
            lastname: '',
            birthday: '',
            address: '',
            zipCode: '',
            city: '',
            country: '',
            password: '',
            currentPassword: '',
            newPassword: '',
            newPasswordConfirm: '',
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

            if (field.name === 'token') {
                if (!field.value) {
                    this.error.record({ token: 'token invalide' });
                }
                //this._contact.token = field.value;
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
                    this.error.record({ firstname: 'Prénom invalide' });
                }
                this._contact.firstname = field.value;
            }

            if (field.name === 'lastname') {
                if (!field.value || field.value.length < 3) {
                    this.error.record({ lastname: 'Nom invalide' });
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
                    this.error.record({ address: 'aAdresse invalide' });
                }
                this._contact.address = field.value;
            }

            if (field.name === 'zipCode') {
                if (!field.value || field.value.length < 3) {
                    this.error.record({ zipCode: 'Code postal invalide' });
                }
                this._contact.zipCode = field.value;
            }

            if (field.name === 'city') {
                if (!field.value || field.value.length < 3) {
                    this.error.record({ city: 'Ville invalide' });
                }
                this._contact.city = field.value;
            }

            if (field.name === 'country') {
                if (!field.value || field.value.length < 3) {
                    this.error.record({ country: 'Pays invalide' });
                }
                this._contact.country = field.value;
            }

            if (field.name === 'password') {
                if (!field.value || field.value.length < 8) {
                    this.error.record({ password: 'Mot de passe invalide' });
                }
                this._contact.password = field.value;
            }

            if (field.name === 'newPasswordConfirm') {
                if (!field.value || field.value.length < 8) {
                    this.error.record({ newPasswordConfirm: 'Mot de passe invalide' });
                }
                this._contact.newPasswordConfirm = field.value;
            }

            if (field.name === 'currentPassword') {
                if (!field.value || field.value.length < 8) {
                    this.error.record({ currentPassword: 'Mot de passe invalide' });
                }
                this._contact.currentPassword = field.value;
            }

            if (field.name === 'newPassword') {
                if (!field.value || field.value.length < 8) {
                    this.error.record({ newPassword: 'Mot de passe invalide' });
                }
                this._contact.newPassword = field.value;
            }

            if (field.name === 'quantity') {
                if (!field.value || !field.value.match(/^\d+$/)) {
                    this.error.record({ quantity: 'Quantité invalide' });
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
        const regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return regex.test(String(email).toLowerCase());
    }
}

export default Form;
