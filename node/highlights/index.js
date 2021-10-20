const jsyaml = require('js-yaml');
const util = require('util');
const fs = require('fs');
const express = require('express');
const https = require('https');
const sio = require('socket.io');
const cors = require('cors');
const lockChecker = require('../lock_checker');
const getSslKeys = require('../get_ssl_keys');

lockChecker('highlights');

const app = express();
app.use(cors());
app.use(express.json({
  type: 'application/json',
}));

app.use(express.urlencoded());

const sslKeys = getSslKeys();

const server = https.createServer({
  key: sslKeys.key,
  cert: sslKeys.certificate,
}, app);

const io = sio(server, {
  cors: {
    origin: '*',
    methods: ['GET', 'POST'],
  },
});

const token = 'hW4aZ9kX2nP6uI8rY4xJ6iM1uZ6gH3vU';
const languages = ['br', 'en'];
const translations = {};

const sprintf = (text, params) => util.format.apply(null, [text].concat(params));

const counters = {
  connecitons: 0,
  broadcasts: 0,
  broadcastsSent: 0,
  currentClients: 0,
};

languages.forEach((lang) => {
  console.log(`[MAIN] Translation '${lang}' is now buffered`);

  const buffer = fs.readFileSync(`${__dirname}/../../include/locales/${lang}.yml`, 'utf8');
  translations[lang] = jsyaml.load(buffer);
});

app.post('/console/write/', (req, res) => {
  let result = 'unknown error';

  console.log(req.headers);
  console.log(req.body, token);
  if (req.body.token !== token) {
    result = 'invalid token';
  } else if (req.body.message) {
    console.log('[WRITE] Will broadcast standard message');

    result = 'ok';
    counters.broadcasts++;

    languages.forEach((lang) => io.sockets.in(`lang_${lang}`).emit('message', {
      message: req.body.message,
    }));
  } else if (req.body.yaml) {
    console.log('[WRITE] Will broadcast translatable message');

    languages.forEach((lang) => {
      const defaultMessage = `-- translation mission: ${req.body.yaml}--`;
      let message = '';

      try {
        message = sprintf(eval(`translations[lang].${lang}.${req.body.yaml}`), req.body.assigns);
      } catch (_error) {
        message = defaultMessage;
      }

      if (message === 'undefined') {
        message = defaultMessage;
      }

      return io.sockets.in(`lang_${lang}`).emit('message', {
        message,
      });
    });
  } else {
    result = 'missing message';
  }

  res.set('Content-Type', 'text/plain');
  return res.send(result);
});

app.get('/status/:token', (req, res) => {
  res.set('Content-Type', 'text/plain');

  return res.send(`Clients connected: ${counters.currentClients}\nOverall connections: ${counters.connecitons}\nBroadcasts: ${counters.broadcasts}\nBroadcasts(Client count): ${counters.broadcastsSent}\n`);
});

io.sockets.on('connection', (socket) => {
  counters.connecitons++;
  counters.currentClients++;

  socket.on('disconnect', () => {
    counters.currentClients--;
  });

  socket.on('set-language', (data) => {
    if (data.lang) {
      socket.join(`lang_${data.lang}`);
    }
  });
});

server.listen(2533);

console.log('[MAIN] Highlights Started');
