const userCRUDModel = require('../models/userCRUD');

const readOneUserQueries = (id) => {
  return userCRUDModel.findById(id).select(['-__v']);
}

module.exports = readOneUserQueries;