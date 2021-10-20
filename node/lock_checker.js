const path = require("path");
const fs = require("fs");
const process = require("process");

process.stdin.resume();

module.exports = function (completeFileName) {
  const fileName = path.basename(completeFileName);
  const root = `${__dirname}/.pids`;
  const lockFile = `${root}/${fileName}.lock`;
  let isRunning = false;

  if (fs.existsSync(lockFile)) {
    const pid = fs.readFileSync(lockFile);

    if (fs.existsSync(`/proc/${pid}`)) {
      isRunning = true;

      console.log("[LOCKCHECKER] Process is running")
    } else {
      console.log("[LOCKCHECKER] Lock file found, but process isn't running");
    }
  }

  if (isRunning) {
    process.exit(0);
    return;
  }

  console.log(`[LOCKCHECKER] Lock checker enabled(${lockFile})`);

  const exitHandler = () => {
    fs.unlinkSync(lockFile);
  };

  process.on("exit ", exitHandler);
  process.on("SIGINT ", exitHandler);
  process.on("SIGUSR1 ", exitHandler);
  process.on("SIGUSR2 ", exitHandler);
  process.on("uncaughtException ", exitHandler);

  fs.writeFileSync(lockFile, process.pid.toString());
}
