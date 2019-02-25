import models from './models';

Vue.mixin({
  data() {
    return {
      // TODO: not used so far
      //currentUser: App.user
    };
  },

  computed: {
    constants() {
      return App.constants;
    },
    csrfToken() {
      return App.csrfToken;
    },
    _() {
      return _;
    },
    models() {
      return models;
    }
  },

  methods: {
    trans(key, params = {}) {
      return this.lang(key, params);
    },

    lang(key, params = {}) {
      return Lang.get(key, params);
    },

    url(url) {
      return App.urls.root + url;
    },

    datetime(datetime) {
      return moment(datetime.date + ' ' + datetime.timezone, 'YYYY-MM-DD HH:mm:ss Z').format('MMMM Do, YYYY h:mm A');
    },

    can(permission, obj) {
      if (
        !Vue.config.silent
        && (
          typeof obj.policy == 'undefined'
          || typeof obj.policy.allowed[permission] == 'undefined'
      )) {
        console.warn("Policy %s not found in object %o", permission, obj);
      }

      return (
        typeof obj.policy != 'undefined'
        && typeof obj.policy.allowed[permission] != 'undefined'
        && !!obj.policy.allowed[permission]
      );
    }
  }
});
