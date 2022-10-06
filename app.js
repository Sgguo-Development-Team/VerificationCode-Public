const express = require("express");
const app = express();
const crypto = require("crypto");
const mysql = require("mysql");
const console = require("console");
const fs = require("fs");

const config = JSON.parse(fs.readFileSync("./config.json"));

console.log("成功加载配置文件");

const connection = mysql.createPool(config["database"]);

console.log("数据库连接池已完成!");

const Hash = (str) => {
  const hash = crypto.createHash("md5");
  hash.update(str);
  return hash.digest("hex");
};

app.get("/", (req, res) => {
  console.log("/ 成功触发 Route");
  res.json({
    code: 200,
    msg: `Server running on http://127.0.0.1:${config["port"]}`,
  });
});

app.get("/api/getVerification/", (req, res) => {
  console.log("/api/getVerification 成功触发 Route");
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
          id: queryResult[0]["id"],
          type: "Chemical",
          value: `https://www.chemicalbook.com/CAS/GIF/${queryResult[0]["cas"]}.gif`,
          question: "请输入上图物质的分子式",
          answer: Hash(queryResult[0]["answer"]),
        },
      };
      console.log(queryResult);
      res.json(result);
    }
  );
});

app.get("/api/getAnswer/:id", (req, res) => {
  console.log("/api/getAnswer/:id 成功触发 Route");
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
      console.log(queryResult);
      res.json(result);
    }
  );
});

app.listen(config["port"], () => {
  console.log(`Server running on http://127.0.0.1:${config["port"]}`);
});
