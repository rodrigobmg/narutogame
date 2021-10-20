var	EverlastCookie	= function (options) {
	this.options	= $.extend({
		key:	'elc_key',
		debug:	false
	}, options);

	this.get	= function (name, cb) {
		var	storages_to_check	= 6;
		var	storages_checked	= 0;
		var	storages_values		= {};
		var	current_key			= this.make_key(name);

		var	_this				= this;

		if (this.options.debug) {
			console.log('ELC: Will query storages');
		}

		for(var i in this.storages) {
			if (this.options.debug) {
				console.log('ELC: Querying storage ' + i);
			}

			var	storage			= this.storages[i];
			var value_check_cb	= function (value, storage_name) {
				storages_values[storage_name]	= value;
				storages_checked++;

				if (_this.options.debug) {
					console.log('ELC: Got response from storage ' + storage_name + ' -> "' + value + '" [' + storages_checked + ' of ' + storages_to_check + ']' );
				}

				if (storages_checked == storages_to_check) {
					var	have_value	= false;

					for(var g in storages_values) {
						var	value	= storages_values[g];

						if (value != null && !have_value) {
							cb.apply(null, [value]);

							have_value	= true;
						}
					}

					if (!have_value) {
						cb.apply(null, [null]);
					}
				}
			};

			storage.apply(null, [current_key, value_check_cb, i]);
		}
	}

	this.set	= function (name, value) {
		for(var i in this.storages) {
			var	storage	= this.storages[i];

			if (this.options.debug) {
				console.log('ELC: Setting value into storage ' + i);
			}

			storage.apply(null, [this.make_key(name), function () {}, i, value]);
		}
	}

	this.make_key	= function (base) {
		return this.options.key + '_' + base;
	}

	return this;
}

EverlastCookie.prototype.storages	= {
	localStorage:	function (name, cb, storage_name, set_value) {
		if (window.localStorage) {
			if (typeof(set_value) != "undefined") {
				localStorage.setItem(name, set_value);
			} else {
				cb.apply(null, [localStorage.getItem(name), storage_name]);
			}
		} else {
			cb.apply(null, [null, storage_name]);
		}
	},

	localDatabase:	function (name, cb, storage_name, set_value) {
		if (window.openDatabase) {
			var db	= window.openDatabase("db_everlast_cookie", "", "db_everlast_cookie", 1024 * 1024);

			if (typeof(set_value) != "undefined") {
				db.transaction(function(tx) {
					tx.executeSql("CREATE TABLE IF NOT EXISTS cache(" +
						"id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, " +
						"name TEXT NOT NULL, " +
						"value TEXT NOT NULL, " +
						"UNIQUE (name)" + 
					")", [], function (tx, rs) { }, function (tx, err) { });

					tx.executeSql(
						"INSERT OR REPLACE INTO cache(name, value) VALUES(?, ?)", [name, set_value],
						function (tx, rs) { },
						function (tx, err) { }
					);
				});
			} else {
				db.transaction(function(tx) {
					tx.executeSql("SELECT value FROM cache WHERE name=?", [name], function(tx, result1) {
						if (result1.rows.length >= 1)
							cb.apply(null, [result1.rows.item(0)['value'], storage_name]);
						else {
							cb.apply(null, [null, storage_name]);
						}
					}, function (tx, err) {
						cb.apply(null, [null, storage_name]);
					});
				});
			}
		} else {
			cb.apply(null, [null, storage_name]);
		}
	},

	sessionStorage:	function (name, cb, storage_name, set_value) {
		if (window.sessionStorage)
		{
			if (typeof(set_value) != "undefined") {
				sessionStorage.setItem(name, set_value);
			} else {
				return cb.apply(null, [sessionStorage.getItem(name), storage_name]);;
			}
		} else {
			cb.apply(null, [null, storage_name]);
		}
	},

	etag:	function (name, cb, storage_name, set_value) {
		cb.apply(null, [null, storage_name]);
	},

	cache:	function (name, cb, storage_name, set_value) {
		cb.apply(null, [null, storage_name]);
	},

	cookie:	function (name, cb, storage_name, set_value) {
		var	variables	= document.cookie.split('; ');
		var	value		= null;
		var	orig_value	= null;

		for (var i = 0; i < variables.length; i++) {
			var	variable	= variables[i].split('=');
			orig_value		= variables[i];

			if (variable[0] == name) {
				value	= variable[1];
			}
		}

		if (typeof(set_value) != "undefined") {
			var	cookie	= name + '=' + encodeURIComponent(set_value);

			document.cookie = name + '=; expires=Mon, 20 Sep 2010 00:00:00 UTC; path=/';
			document.cookie = cookie + '; expires=Tue, 31 Dec 2030 00:00:00 UTC; path=/';
		} else {
			cb.apply(null, [value, storage_name]);
		}
	}
}