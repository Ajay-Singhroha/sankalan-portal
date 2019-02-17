export default {
    data() {
        return {
            isRegister: true
        }
    },
    methods: {
        login() {
            this.isRegister = false;
            window.location.hash = 'login';
        },
        register() {
            this.isRegister = true;
            window.location.hash = 'register';
        }
    },
    mounted() {
        if(window.location.hash == '#login') {
            this.isRegister = false;
        }
    }
}

