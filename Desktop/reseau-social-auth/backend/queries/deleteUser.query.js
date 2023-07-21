const userCRUDModel = require('../models/userCRUD');

const deleteUserQuery = (id, data) => {
  return userCRUDModel
  .findOneAndUpdate(id,data).select(['-__v'])
}

module.exports = deleteUserQuery