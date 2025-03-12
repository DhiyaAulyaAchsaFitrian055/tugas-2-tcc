import { Sequelize } from "sequelize";

const db = new Sequelize("notes_dhidi", "root", "", {
  host: "34.71.187.139",
  dialect: "mysql",
});

export default db;
