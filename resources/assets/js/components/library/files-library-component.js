const
  prettyBytes = require('pretty-bytes'),
  modal = require('vue-strap/dist/vue-strap.min').modal,
  tooltip = require('vue-strap/dist/vue-strap.min').tooltip,
  spinner = require('../vendor/vue-strap/Spinner.vue');

// TODO: not needed for now
//const vsFileUpload = require('gritcode-components/dist/gritcode-components').fileUpload;

let Component = {
  props: ['tagName'],

  components: {
    modal,
    tooltip,
    spinner

    // TODO: not needed for now
    // vsFileUpload
  },

  ready() {
    this.loadPage(1);
  },

  data() {
    return {
      model: null,
      fileList: [],
      files: {
        data: [],
        meta: {}
      },
      currentPage: 1,
      filters: {
        search: null
      },
      showPreviewFile: false,
      previewFile: null,
      showConfirmationModal: false,
      onChooseCallback: null
    }
  },

  methods: {

    search() {
      this.loadPage(1);
    },

    reloadPage() {
      this.loadPage();
    },

    loadPage(page) {
      if (typeof page != 'undefined') {
        this.currentPage = page;
      }

      let model  = null;
      if (
        this.tagName == 'print-files-library'
        || this.tagName == 'print-files-library-back'
      ) {
        model = App.models.library.prints;
      }
      else {
        model = App.models.library.sources;
      }

      //this.$refs.spinner.show();
      model.getPage(this.currentPage, this.filters, (response) => {
        //this.$refs.spinner.hide();
        this.files = response.data.files;
      }, () => {
        //this.$refs.spinner.hide();
      });
    },

    uploadFile(e) {
      let
        form = $('.js-print-files-library-upload-form', this.$el),
        model  = null;

      if (typeof this.loader == 'undefined') {
        this.loader = Ladda.create($('.btn',form).get(0));
      }
      this.loader.start();

      if (
        this.tagName == 'print-files-library'
        || this.tagName == 'print-files-library-back'
      ) {
        model = App.models.library.prints;
      }
      else {
        model = App.models.library.sources;
      }

      let data = new FormData();
      data.append('file', $('input',form).get(0).files[0]);

      model.uploadFile(data, (response) => {
        this.reloadPage();
        this.loader.stop();
      }, () => {
        this.loader.stop();
      });
    },

    deleteFile(file_id) {
      App.models.library.deleteFile(file_id, (response) => {
        this.previewFile = null;
        this.showPreviewFile = false;
        this.showConfirmationModal = false;

        this.reloadPage();
      });
    },

    openPreviewModal(file) {
      this.previewFile = file;
      this.showPreviewFile = true;
    },

    prettyBytes(arg) {
      return prettyBytes(arg);
    },

    setOnChooseCallback(cb) {
      this.onChooseCallback = cb;
    }
  }
};

Vue.component('print-files-library', Object.assign({}, Component));
Vue.component('source-files-library', Object.assign({}, Component));
Vue.component('print-files-library-back', Object.assign({}, Component));
Vue.component('source-files-library-back', Object.assign({}, Component));
