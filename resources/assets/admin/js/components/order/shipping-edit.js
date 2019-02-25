const
  Multiselect = require('../../../../js/vendor/vue-multiselect'),
  variantOptionTemplate = `<div>
    <div class="option__desc">
      <img v-if="option.image" class="w-20" :src="option.image" alt="" />
      <span class="option__title">{{ option.name }}</span>
    </div>
  </div>`;
  if (window.Vue === undefined) {
    window.Vue = require('vue');
  }  
Vue.partial('customOptionPartial', variantOptionTemplate);

Vue.component('admin-orders-shipping-edit', {

  components: {
    'multiselect': Multiselect
  },

  data() {
    return {
      allAvailableOptions: App.data.ProductModelTemplates,
      availableOptions: [],
      selectedOptions: App.data.CurrentShippingSetting.templates
    }
  },

  ready() {
    this.$set('availableOptions', this.allAvailableOptions);
  },

  methods: {
    onOptionSelected(selected) {
      this.selectedOptions = selected;
    },

    styleTemplateSelectLabel(option) {
      let image = '';
      if (option.image) {
        image = `
          <img class="w-20" src="${option.image}" alt="" />
        `;
      }

      return `
        <div>
          <div class="option__desc">
            ${image}
            <span class="option__title">${ option.name }</span>
          </div>
        </div>
      `;
    }
  }

});
