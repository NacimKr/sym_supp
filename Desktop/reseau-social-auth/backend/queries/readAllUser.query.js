const userCRUDModel = require('../models/userCRUD')

const readUserQuery = () => userCRUDModel.find({}).select(['-__v']);

module.exports = readUserQuery