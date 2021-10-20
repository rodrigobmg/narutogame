const express = require('express');
const https = require('https');
const crypto = require('crypto');
const sio = require('socket.io');
const mysql = require('mysql2');
const cors = require('cors');
const phpjs = require('./phpjs');
const emoticons = require('./emoticons');
const lockChecker = require('../lock_checker');
const getSslKeys = require('../get_ssl_keys');

lockChecker('chat');

const mysqlConfig = {
  database: 'naruto_db',
  user: 'root',
  password: '',
};

const users = {};
const usersByName = {};
const channels = {};
const privates = {};
const counters = {};
const lastMessages = {};
const maxMessages = 100;
const db = mysql.createConnection(mysqlConfig);
let userMessageSize = 140;
let banned = [];
let blacklist = [];

const app = express();
const sslKeys = getSslKeys();
app.use(cors());

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

const bootstrap = () => {
  channels.world = [];

  server.listen(2934);
  console.log(`Process started with PID ${process.pid}`);
};

const decryptJson = (encrypted) => {
  const key = 'vH1cKwU3X27cmS1jIoE1g6f2nA3PZ2bU';
  const iv = key.substr(0, 16);
  const decoded = Buffer.from(encrypted, 'base64').toString('utf-8');
  const decipher = crypto.createDecipheriv('aes-256-cbc', key, iv);

  decipher.setAutoPadding(false);

  let decrypted = decipher.update(decoded, 'base64');
  decrypted += decipher.final('base64');

  // eslint-disable-next-line no-control-regex
  decrypted = decrypted.replace(/[\u0000-\u001F\u007F-\u009F]/g, '');

  return JSON.parse(decrypted);
};

const diffInSeconds = (d1, d2) => {
  let diff = d2 - d1;
  let milliseconds = 0;
  let seconds = 0;
  let minutes = 0;
  let hours = 0;
  let days = 0;
  const sign = diff < 0 ? -1 : 1;

  diff /= sign;
  diff = (diff - (milliseconds = diff % 1000)) / 1000;
  diff = (diff - (seconds = diff % 60)) / 60;
  diff = (diff - (minutes = diff % 60)) / 60;
  days = (diff - (hours = diff % 24)) / 24;

  return {
    milliseconds,
    seconds,
    minutes,
    hours,
    days,
  };
};

io.sockets.on('connection', (socket) => {
  console.log('[SIO] Connection');

  let uid;
  let user;

  socket.on('register', (data) => {
    console.log('[CHAT] Register');

    const decryptedData = decryptJson(data.data);

    if (data) {
      socket.join(`village_${decryptedData.village}`);
      socket.join(`org_${decryptedData.org}`);
      socket.join(`team_${decryptedData.team}`);
      socket.join(`battle_${decryptedData.battle}`);
      socket.join(`battle4_${decryptedData.battle4}`);
      socket.join('r10_0');
      socket.join('world_0');

      decryptedData.last_activity = new Date();

      users[decryptedData.uid] = decryptedData;
      usersByName[decryptedData.name.toLowerCase()] = users[decryptedData.uid];

      uid = decryptedData.uid;
      user = users[decryptedData.uid];

      counters[decryptedData.uid] = true;

      ['village', 'org', 'team', 'battle', 'battle4', 'world', 'r10'].forEach((channel) => {
        let channelId;

        if (['world', 'r10'].indexOf(channel) !== -1) {
          channelId = 0;
        } else {
          channelId = decryptedData[channel];
        }

        if (channels[channel] && channels[channel][channelId]) {
          const { messages } = channels[channel][channelId];

          Object.values(messages).forEach((message) => {
            socket.emit('broadcast', message);
          });
        }
      });
    }
  });

  socket.on('pvt-was-read', (data) => {
    const pvtIndex = Object.keys(privates[uid])[data.index];

    if (privates[uid] && privates[uid][pvtIndex]) {
      delete privates[uid][pvtIndex];
    }
  });

  socket.on('message', async (data) => {
    const messageData = { ...data };
    const userInstance = users[uid];

    if (!userInstance) {
      console.log('invalid user trying to send a message');
      return;
    }

    // Check for blacklisted words in the message
    if (!user.gm) {
      let hasBlacklistedWord = false;

      blacklist.forEach((word) => {
        if (data.message.match(new RegExp(word, 'img')) && !hasBlacklistedWord) {
          hasBlacklistedWord = true;

          socket.emit('broadcast', {
            from: 'Sistema',
            message: 'A mensagem contem palavras impróprias',
            channel: 'warn',
          });
        }
      });

      if (hasBlacklistedWord) {
        return;
      }
    }

    if (!user.gm) {
      messageData.message = phpjs.htmlspecialchars(messageData.message);
    }

    // Send private message
    if (messageData.channel === 'private') {
      if (!data.dest.toString().match(/^\d$/)) {
        messageData.dest = data.dest.replace(/\s/, '');

        if (!usersByName[data.dest.toLowerCase()]) {
          socket.emit('broadcast', {
            from: 'Sistema',
            message: `Usuário "${data.dest}" indisponível para enviar mensagens`,
            channel: 'warn',
          });

          return;
        }

        messageData.dest = usersByName[data.dest.toLowerCase()].uid;
      }

      // Check if the destination user has blocked the one that's sending the message
      if (users[messageData.dest]) {
        const [rows] = await db.promise().query(`SELECT id FROM chat_blocked WHERE id_user=${messageData.dest} AND id_user_blocked=${user.user_id}`);

        console.log("-------------------------", rows);

        if (rows.length) {
          socket.emit('broadcast', {
            from: 'Sistema',
            message: 'Você não pode enviar mensagens para esse usuário',
            channel: 'warn',
          });

          return;
        }
      }

      if (!privates[messageData.dest]) {
        privates[messageData.dest] = {};
      }

      privates[messageData.dest][Math.random() * 512384] = {
        name: user.name,
        message: data.message,
        id: user.uid,
      };

      console.log(privates);

      return;
    }

    // Block action
    if (data.channel === 'block') {
      const userToBlock = usersByName[data.message.toLowerCase()];

      if (!userToBlock) {
        socket.emit('broadcast', {
          from: 'Sistema',
          message: `Usuário "${data.message}" não encontrado`,
          channel: 'warn',
        });

        return;
      }

      if (userToBlock.gm) {
        socket.emit('broadcast', {
          from: 'Sistema',
          message: 'Você não pode bloquear esse jogador pois ele pertence a STAFF',
          channel: 'warn',
        });

        return;
      }

      db.promise().query(`INSERT INTO chat_blocked(id_user, id_user_blocked) VALUES(${user.user_id}, ${u.user_id})`).then(() => {
        socket.emit('broadcast', {
          from: 'Sistema',
          message: `Usuário "${data.message}" bloqueado com sucesso`,
          channel: 'warn',
        });
      });
    }

    let channelId;

    // eslint-disable-next-line default-case
    switch (data.channel) {
      case 'village':
        channelId = user.village;
        break;

      case 'org':
        channelId = user.org;
        break;

      case 'team':
        channelId = user.team;
        break;

      case 'battle':
        channelId = user.battle;
        break;

      case 'battle4':
        channelId = user.battle4;
        break;
    }

    if (!channelId && !['world', 'r10'].indexOf(data.channel) === -1) {
      console.log('channel error');
      return;
    }

    if (user.user_id && banned[user.user_id]) {
      socket.emit('broadcast', {
        from: 'Sistema',
        message: 'Você foi banido do chat',
        channel: 'warn',
      });

      return;
    }

    if (user.gm) {
      messageData.message = emoticons.parse(messageData.message, user.gm);
    } else {
      const now = new Date();
      const sendingTooOften = lastMessages[user.user_id] && diffInSeconds(lastMessages[user.user_id], now).seconds < 10;

      if (sendingTooOften) {
        socket.emit('broadcast', {
          from: 'Sistema',
          message: 'Você deve aguardar 10 segundos antes de enviar uma nova mensagem',
          channel: 'warn',
        });
        return;
      }

      lastMessages[user.user_id] = now;

      if (messageData.channel === 'r10') {
        userMessageSize = 500;
      }

      messageData.message = emoticons.parse(messageData.message.substr(0, userMessageSize), user.gm);
    }

    if (!channelId) {
      channelId = 0;
    }

    const broadcast = {
      from: user.name,
      message: data.message,
      channel: data.channel,
      channel_id: channelId,
      id: user.uid,
      user_id: user.user_id,
      gm: user.gm,
      avatar: user.avatar,
      when: new Date(),
    };

    if (messageData.channel === 'village') {
      broadcast.color = user.color;
      broadcast.icon = user.icon;
    }

    if ((messageData.channel === 'org' && user.org_owner) || (messageData.channel === 'team' && user.team_owner)) {
      broadcast.color = '#BF2121';
    }

    if (!channels[messageData.channel]) {
      channels[messageData.channel] = {};
    }

    if (!channels[messageData.channel][channelId]) {
      channels[messageData.channel][channelId] = {
        last: new Date(),
        messages: [],
      };
    }

    channels[data.channel][channelId].messages.last = new Date();
    channels[data.channel][channelId].messages.push(broadcast);

    if (channels[data.channel][channelId].messages.length > maxMessages) {
      const messageDiff = channels[data.channel][channelId].messages.length - maxMessages;

      (() => {
        const results = [];
        for (let j = 0; messageDiff >= 0 ? j <= messageDiff : j >= messageDiff; messageDiff >= 0 ? j++ : j--) {
          results.push(j);
        }
        return results;
      }).apply(this).forEach(() => channels[data.channel][channelId].messages.shift());
    }

    io.sockets.in(`${data.channel}_${channelId}`).emit('broadcast', broadcast);
  });

  socket.on('blocked-query', () => {
    if (!users[uid]) {
      return;
    }

    db
      .promise()
      .query(`SELECT id_user_blocked FROM chat_blocked WHERE id_user=${users[uid].user_id}`).then(([rows]) => {
        socket.emit('blocked-broadcast', rows.map((row) => row.id_user_blocked));
      });
  });

  // Get private messages
  socket.on('pvt-query', () => {
    console.log(`[CHAT] Get private messages - ${uid}`);

    if (!users[uid]) {
      return;
    }

    const privateMessages = Object.values(privates[uid] || {});
    const broadcast = privateMessages.map((message, i) => ({
      from: message.name,
      message: message.message,
      id: message.id,
      index: i,
    }));

    if (broadcast.length) {
      socket.emit('pvt-broadcast', broadcast);
    }
  });

  socket.on('disconnect', () => {
    if (!users[uid]) {
      return;
    }

    counters[uid] = false;

    socket.leave(`village_${users[uid].village}`);
    socket.leave(`org_${users[uid].org}`);
    socket.leave(`team_${users[uid].team}`);
    socket.leave(`battle_${users[uid].battle}`);
    socket.leave(`battle4_${users[uid].battle4}`);
    socket.leave('world_0');
  });
});

/**
 * Wold blacklist checking
 */
setInterval(() => {
  console.log('[INTERVAL] Update word blacklist');

  const words = db.query('SELECT * FROM word_blacklist');

  blacklist = [];
  return words.addListener('row', (row) => blacklist.push(row.expr));
}, 2000);

/**
 * Banned user check
 */
setInterval(() => {
  console.log('[INTERVAL] Updating banned user list');

  const banlist = db.query('SELECT * FROM chat_banned');

  banned = [];
  banlist.addListener('row', (row) => {
    banned[row.id_usuario] = true;
  });
}, 5000);

/**
 * Clear battle channels
 */
setInterval(() => {
  if (!channels.battle) {
    return;
  }

  console.log('[INTERVAL] Clearing battles');

  Object.entries(channels.battle).forEach(([key, battle]) => {
    const isPast = new Date((new Date()).setMinutes((new Date()).getMinutes() - 30));

    if (battle.past < isPast) {
      delete channels.battle[key];
    }
  });
}, 5000);

/**
 * Activity timer
 */
setInterval(() => {
  console.log('[INTERVAL] Updating time played');

  Object.entries(counters).forEach(([user, counter]) => {
    if (!counter) {
      return;
    }

    db.query(`UPDATE played_time SET minutes=minutes+1 WHERE id_player=${user}`);
  });
}, 60000);

bootstrap();
