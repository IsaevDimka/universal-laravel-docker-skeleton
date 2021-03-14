/**
 * for use this mixin use method makeErrors(data,formReference) in axios.catch
 */
export default {
    data () {
        return {
            errors: {},
            errorsMessage: 'Form validation error'
        }
    },
    methods: {
        /**
         * @param e
         * @param form VueComponent
         */
        makeErrors(e, form ) {
            form.clearValidate();
            console.log(e)
            this.errors = {}
            if (e.response && e.response.status === 422) {
                const errors = e.response.data.errors;
                this.errorsMessage = e.response.data.message;
                this.errors = {};
                for ( const prop in errors ) {
                    if (errors.hasOwnProperty(prop)) {
                        this.errors[prop] = errors[prop].join(' ');
                        const field = form.fields.find(item => item.prop === prop);
                        if (field) {
                            field.validateState = 'error';
                            field.validateMessage = this.errors[prop];
                        }
                    }
                }
            }
            else {
                // Other exception messages
                this.errorsMessage = ''
            }
            this.validationErrorMessage();
        },
        validationErrorMessage () {
            this.$message({
                showClose: true,
                message: this.errorsMessage,
                type: 'error',
                offset: 73,
                duration: 5000,
            });
        },

    }
}
