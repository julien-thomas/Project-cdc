import Errors from './Errors.js';

class FormAdmin {
    constructor() {
        this.error = new Errors();
        this.isValid = false;
        this.allowed = ['token', 'name', 'title', 'stock', 'price', 'grape' ,'country', 'vintage', 'MAX_FILE_SIZE', 'upload'];
        this.name = '';
        this.title = '';
        this.stock = '';
        this.price = '';
        this.grape = '';
        this.country = '';
        this.vintage = '';
        this._contact = {
            name: '',
            title: '',
            stock: '',
            price: '',
            grape: '',
            country: '',
            vintage: ''
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
                    
            if (field.name === 'name') {
                if (!field.value || field.value.length < 3 || field.value.length > 50 || !field.value.match(/^[A-Za-zÀ-ÿ0-9,.'\s]+$/)) {
                    this.error.record({ name: 'Nom invalide' });
                }
                this._contact.name = field.value;
            }

            if (field.name === 'title') {
                if (!field.value || field.value.length < 3 || field.value.length > 50 || !field.value.match(/^[A-Za-zÀ-ÿ,.'\s]+$/)) {
                    this.error.record({ title: 'Appellation invalide' });
                }
                this._contact.title = field.value;
            }

            if (field.name === 'stock') {
                if (!field.value || !field.value.match(/^\d+$/) || field.value > 99999) {
                    this.error.record({ stock: 'Stock invalide' });
                }
                this._contact.stock = field.value;
            }

            if (field.name === 'price') {
                if (!field.value || !field.value.match(/^\d+(\.\d{1,2})?$/)) {
                    this.error.record({ price: 'Prix invalide' });
                }
                this._contact.price = field.value;
            }

            if (field.name === 'grape') {
                if (!field.value || field.value.length < 3 || field.value.length > 50 || !field.value.match(/^[A-Za-zÀ-ÿ,.\s]+$/)) {
                    this.error.record({ grape: 'Cépage invalide' });
                }
                this._contact.grape = field.value;
            }

            if (field.name === 'country') {
                if (!field.value || field.value.length < 3 || field.value.length > 50 || !field.value.match(/^[A-Za-zÀ-ÿ,.\s]+$/)) {
                    this.error.record({ country: 'Pays invalide' });
                }
                this._contact.country = field.value;
            }

            if (field.name === 'vintage') {
                if (!field.value || !field.value.match(/^\d+$/) || field.value.length != 4) {
                    this.error.record({ vintage: 'Millésime invalide' });
                }
                this._contact.vintage = field.value;
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
    
}

export default FormAdmin;
