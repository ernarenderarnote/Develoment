module.exports = Vue.extend({
  name: 'attribute-options-tree',
  template: '#js-attribute-options-tree-tmpl',
  props: [
    'options',
    'firstLevel',
    'selectedModelIds',
    'multiple',
    'optionPrefix',
    'selectedColorsValues',
    'selectedSizesValues',
    'hasOnlyOneColorOption'
  ],
  
  computed: {
    _() {
      return _;
    },
    constants() {
      return App.constants;
    }
  },
  
  methods: {
    first(options) {
      return _.first(_.toArray(options));
    }
  }
});
