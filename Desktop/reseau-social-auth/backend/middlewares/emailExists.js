const { validationResult } = require('express-validator')
const { validateEmail } = require('./validator')


module.exports = async (req, res) => {
    const errors = validationResult(req);

    if (!errors.isEmpty()) {
        return res.send(signuconst({ errors }))
    }

    const { email, password } = req.body
    
    await repo.create({ email, password })

    res.send('Sign Up successfully')
}