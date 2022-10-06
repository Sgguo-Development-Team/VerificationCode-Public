const express = require("express");
const app = express();
const mysql = require("mysql");
const crypto = require("crypto");

const connection = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "RootDBUser",
  port: "3306",
  database: "verification",
});

// 连接 Db
connection.connect();

const Hash = (str) => {
  const hash = crypto.createHash("md5");
  hash.update(str);
  return hash.digest("hex");
};

app.get("/api/getVerification/", (req, res) => {
  connection.query(
    `SELECT * FROM sd_vcode ORDER BY rand() LIMIT 1`,
    (err, queryResult) => {
      if (err) {
        console.log("[SELECT ERROR] - ", err.message);
        res.status(502).send(err);
      }
      const result = {
        code: 200,
        data: {
          ID: queryResult[0]["id"],
          Type: "Chemical",
          Value: `https://www.chemicalbook.com/CAS/GIF/${queryResult[0]["cas"]}.gif`,
          Question: "请输入上图物质的分子式",
          Answer: Hash(queryResult[0]["answer"]),
        },
      };
      res.jsonp(result);
    }
  );
});

app.get("/api/getAnswer/:id", (req, res) => {
  const id = req.params["id"] ?? res.status(403).send("请指定 ID");
  connection.query(
    `SELECT answer FROM sd_vcode WHERE id = ${id}`,
    (err, queryResult) => {
      if (err) {
        console.log("[SELECT ERROR] - ", err.message);
        res.status(502).send(err);
      }
      const result = {
        code: 200,
        answer: queryResult[0]["answer"],
        md5: Hash(queryResult[0]["answer"]),
      };
      res.json(result);
    }
  );
});

app.listen(5500, () => {
  console.log("Express server running on http://127.0.0.1:5500");
});
