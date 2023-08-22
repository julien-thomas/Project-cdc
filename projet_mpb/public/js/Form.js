import Errors from './Errors.js';

class Form {
    constructor() {
        this.error = new Errors();
        this.isValid = false;
        this.allowed = 
        ['token', 'mail', 'firstname', 'lastname', 'birthday', 'address' ,'zipCode', 'city', 'country', 'password',
        'currentPassword', 'newPassword', 'newPasswordConfirm', 'quantity', 'name', 'title', 'opinion', 'content'];
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
        this.name = '';
        this.title = '';
        this.opinion = '';
        this.content = '';
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
            currentPassword: '',
            newPassword: '',
            newPasswordConfirm: '',
            quantity: '',
            name: '',
            title: '',
            opinion: '',
            content: ''
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
            }

            if (field.name === 'mail') {
                if (this.validateEmail(field.value) && field.value.length < 161) {
                    this.isValid = true;
                    this._contact.mail = field.value;
                } else {
                    this.error.record({ mail: 'Email invalide' });
                }
            }
            
            if (field.name === 'firstname') {
                if (!field.value || field.value.length < 3 || field.value.length > 100 || !field.value.match(/^[A-Za-z\-\']+$/)) {
                    this.error.record({ firstname: 'Prénom invalide' });
                }
                this._contact.firstname = field.value;
            }

            if (field.name === 'lastname') {
                if (!field.value || field.value.length < 3 || field.value.length > 100 || !field.value.match(/^[A-Za-z\-\']+$/)) {
                    this.error.record({ lastname: 'Nom invalide' });
                }
                this._contact.lastname = field.value;
            }

            if (field.name === 'birthday') {
                if (this.calculateAge(field.value) >= 18 && this.calculateAge(field.value) < 120) {
                    this.isValid = true;
                    this._contact.birthday = field.value;
                } else {
                    this.error.record({ birthday: 'Age invalide' });
                }
            }

            if (field.name === 'address') {
                if (!field.value || field.value.length < 2 || field.value.length > 200) {
                    this.error.record({ address: 'adresse invalide' });
                }
                this._contact.address = field.value;
            }

            if (field.name === 'zipCode') {
                if (!field.value || field.value.length < 2 || field.value.length > 15) {
                    this.error.record({ zipCode: 'Code postal invalide' });
                }
                this._contact.zipCode = field.value;
            }

            if (field.name === 'city') {
                if (!field.value || field.value.length < 2 || field.value.length > 50 || !field.value.match(/^[A-Za-z\-\']+$/)) {
                    this.error.record({ city: 'Ville invalide' });
                }
                this._contact.city = field.value;
            }

            if (field.name === 'country') {
                if (!field.value || field.value.length < 2  || field.value.length > 50 || !field.value.match(/^[A-Za-z\-\']+$/)) {
                    this.error.record({ country: 'Pays invalide' });
                }
                this._contact.country = field.value;
            }

            if (field.name === 'password') {
                if (!field.value || field.value.length < 8 || field.value.length > 100) {
                    this.error.record({ password: 'Mot de passe invalide' });
                }
                this._contact.password = field.value;
            }

            if (field.name === 'newPasswordConfirm') {
                if (!field.value || field.value.length < 8 || field.value.length > 100) {
                    this.error.record({ newPasswordConfirm: 'Mot de passe invalide' });
                }
                this._contact.newPasswordConfirm = field.value;
            }

            if (field.name === 'currentPassword') {
                if (!field.value || field.value.length < 8 || field.value.length > 100) {
                    this.error.record({ currentPassword: 'Mot de passe invalide' });
                }
                this._contact.currentPassword = field.value;
            }

            if (field.name === 'newPassword') {
                if (!field.value || field.value.length < 8 || field.value.length > 100) {
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

            if (field.name === 'pseudo') {
                if (!field.value || field.value.length < 2 || field.value.length > 20) {
                    this.error.record({ pseudo: 'Pseudo invalide' });
                }
                this._contact.pseudo = field.value;
            }

            if (field.name === 'title') {
                if (!field.value || field.value.length < 2 || field.value.length > 20) {
                    this.error.record({ title: 'Avis invalide' });
                }
                this._contact.title = field.value;
            }

            if (field.name === 'opinion') {
                if (!field.value || field.value.length < 2 || field.value.length > 20) {
                    this.error.record({ opinion: 'Avis invalide' });
                }
                this._contact.opinion = field.value;
            }

            if (field.name === 'content') {
                if (!field.value || field.value.length > 300) {
                    this.error.record({ content: 'Message invalide' });
                }
                this._contact.content = field.value;
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
        const regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return regex.test(String(email).toLowerCase());
    }

    calculateAge(birthday) {
        const DOB_Date = new Date(birthday);
        const now_Date = new Date();
        const diff = now_Date - DOB_Date;
        const AgeInYears = Math.floor (diff / (1000*60*60*24*365.25));
        return AgeInYears;
    }

}

export default Form;
