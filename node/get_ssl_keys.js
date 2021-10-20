const fs = require("fs");

module.exports = function () {
  let key = "";
  let keyMtime = 0;
  let certificate = "";
  let certificateMtime = 0;

  const keys = fs.readdirSync("/home/narutogame/ssl/keys");
  keys.reverse().forEach((keyFile) => {
    const fullFile = `/home/narutogame/ssl/keys/${keyFile}`;
    const mtime = fs.statSync(fullFile).mtime;

    console.log(`[KEY] ${fullFile}`);

    if (mtime > keyMtime) {
      key = fs.readFileSync(fullFile);
      keyMtime = mtime;
    }
  });

  const certificates = fs.readdirSync("/home/narutogame/ssl/certs/");
  certificates.reverse().forEach((certFile) => {
    const fullFile = `/home/narutogame/ssl/certs/${certFile}`;
    const mtime = fs.statSync(fullFile).mtime;

    console.log(`[CRT] ${fullFile}`);

    if (mtime > certificateMtime) {
      certificate = fs.readFileSync(fullFile);
      certificateMtime = mtime;
    }
  });

  return {
    key, certificate
  }
};
