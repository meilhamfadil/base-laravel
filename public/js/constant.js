const VALIDATOR = {
    email: {
        required: true,
        email: true,
    },
    password: {
        required: true,
        minlength: 5
    }
}

const VALIDATOR_MESSAGES = {
    email: {
        required: 'Email wajib diisi',
        email: 'Mohon masukan email yang valid'
    },
    password: {
        required: 'Kata kunci wajib diisi',
        minlength: 'Kata kunci minimal 5 huruf'
    }
}