const
  Multiselect = require('../../../../js/vendor/vue-multiselect'),
  variantOptionTemplate = `<div>
    <div class="option__desc">
      <img v-if="option.mockups && option.mockups[0]" class="w-20" :src="option.mockups[0].thumb" alt="" />
      <span class="option__title">{{ option.name }}</span>
      -
      <span v-for="catalogOption in option.model.options">
        {{ catalogOption.name }}
        <small class="text-muted">
          {{ catalogOption.attribute.name }}
        </small>
      </span>
    </div>
  </div>`;
Vue.partial('customOptionPartial', variantOptionTemplate);

Vue.component('admin-product-show', {

  components: {
    'multiselect': Multiselect
  },

  data() {
    return {
      allAvailableVariants: App.data.CurrentProduct.variants,
      availableVariants: {},
      selectedVariants: {},
      declineSelected: false
    }
  },

  ready() {
    let
      availableVariants = {},
      selectedVariants = {};

    _.each(App.data.CurrentProduct.clientFiles, (clientFile, key) => {
        availableVariants[clientFile.design_location] = this.allAvailableVariants;
        selectedVariants[clientFile.design_location] = {};
      _.each(App.data.ProductDesignerFileTypes, (val, key) => {
        selectedVariants[clientFile.design_location][key] = [];
      });
    });
    this.$set('selectedVariants', selectedVariants);
    this.$set('availableVariants', availableVariants);

    _.each(App.data.CurrentProduct.clientFiles, (clientFile) => {
      _.each(clientFile.designerFiles, (designerFile) => {

        if (!designerFile.file) {
          return;
        }

        this.onVariantSelected(
          designerFile.variants,
          clientFile.design_location+'--'+designerFile.file.type
        );
      });
    });
  },

  methods: {
    onVariantSelected(selected, designLocationAndFileType) {

      designLocationAndFileType = designLocationAndFileType.split('--');

      let
        designLocation = designLocationAndFileType[0],
        fileType = designLocationAndFileType[1];

      let
        selectedVariants = JSON.parse(JSON.stringify(this.selectedVariants[designLocation])),
        availableVariants = JSON.parse(JSON.stringify(this.availableVariants[designLocation])),
        previouslySelected = typeof selectedVariants[fileType] != 'undefined'
          ? JSON.parse(JSON.stringify(selectedVariants[fileType]))
          : {},
        selectedIds = _.pluck(selected, 'id');

      selectedVariants[fileType] = selected;

      availableVariants = availableVariants.concat(previouslySelected);
      availableVariants = _.filter(availableVariants, (variant) => {
        return _.indexOf(selectedIds, variant.id) == -1;
      });

      Vue.set(this.selectedVariants, designLocation, selectedVariants);
      Vue.set(this.availableVariants, designLocation, availableVariants);
    },

    styleVariantSelectLabel(option) {

      let attributesHTML = '';
      _.each(option.model.options, (catalogOption) => {
        attributesHTML += `<span>
            ${ catalogOption.name }
            <small class="text-muted">
              ${ catalogOption.attribute.name }
            </small>
          </span>`;
      });

      let image = '';

      if (option.mockups && option.mockups[0]) {
        image = `
          <img class="w-20" src="${option.mockups[0].thumb}" alt="" />
        `;
      }

      return `
        <div>
          <div class="option__desc">
            ${image}
            <span class="option__title">${ option.name }</span>
            -
            ${attributesHTML}
          </div>
        </div>
      `;
    }
  }

});
