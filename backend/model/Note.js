import { Sequelize } from "sequelize";
import db from "../config/Database.js";

const Note = db.define(
  "notes",
  {
    title: {
      type: Sequelize.STRING,
    },
    content: {
      type: Sequelize.TEXT,
    },
  },
  {
    timestamps: true,
  }
);

export default Note;

(async () => {
  await db.sync();
})();
