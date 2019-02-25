<script>

const
  spinner = require('components/vendor/vue-strap/Spinner.vue'),
  Multiselect = require('js/vendor/vue-multiselect'),
  variantOptionTemplate = `<div>
    <div class="option__desc">
      <img v-if="option.photo_url" class="w-20" :src="option.photo_url" alt="" />
      <img v-if="option.image" class="w-20" :src="option.image" alt="" />
      <span class="option__title">{{ option.name }}</span>
    </div>
  </div>`;
Vue.partial('customOptionPartial', variantOptionTemplate);

const
  models = require('admin/js/models');

module.exports = {

  name: 'price-modifiers',

  props: {
    isOnUserPage: {
      type: Boolean,
      default: false
    }
  },

  components: {
    'multiselect': Multiselect,
    spinner
  },

  data() {
    return {
      priceModifiers: [],
      users: [],
      templates: [],
      template: {},
      user: {},
      modifier: 0,
      selected: {}
    };
  },

  computed: {
    availableOptions() {
      let
        availableOptions = [],
        assignedUsers = [],
        assignedTemplates = [],
        users = [];

      _.each(this.priceModifiers, (priceModifier) => {
        assignedUsers.push(priceModifier.user.id);
        assignedTemplates.push(priceModifier.template.id);
      });

      if (this.isOnUserPage) {
        availableOptions = _.filter(this.templates, (template) => {
          return assignedTemplates.indexOf(template.id) == -1;
        });
      }
      else {
        availableOptions = _.filter(this.users, (user) => {
          return assignedUsers.indexOf(user.id) == -1;
        });
      }

      availableOptions.unshift({
        id: 'all',
        name: Lang.get('labels.select_all')
      });

      return availableOptions;
    },

    selectedUserIds() {
      let ids = [];

      if (this.isOnUserPage) {
        ids.push(this.user.id);
      }
      else {
        if (this.selected.id == 'all') {
          _.each(this.users, (user) => {
            ids.push(user.id);
          });
        }
        else if (typeof this.selected.id != 'undefined') {
          ids.push(this.selected.id);
        }
      }

      return ids;
    },

    selectedTemplateIds() {
      let ids = [];

      if (this.isOnUserPage) {
        if (this.selected.id == 'all') {
          _.each(this.templates, (template) => {
            ids.push(template.id);
          });
        }
        else if (typeof this.selected.id != 'undefined') {
          ids.push(this.selected.id);
        }
      }
      else {
        ids.push(this.template.id);
      }

      return ids;
    }
  },

  ready() {
    this.priceModifiers = App.data.PriceModifiers;

    if (this.isOnUserPage) {
      this.templates = App.data.ProductModelTemplates;
      this.user = App.data.User;
    }
    else {
      this.template = App.data.ProductModelTemplate;
      this.users = App.data.Users;
    }
  },

  methods: {

    onOptionSelected(selected) {
      this.selected = selected;
    },

    addPriceModifier() {

      if (
        !this.modifier
        || this.selectedTemplateIds.length < 1
        || this.selectedUserIds.length < 1
      ) {
        return;
      }

      this.$refs.spinner.show();
      models.priceModifiers.add({
          'modifier': this.modifier,
          'template_ids': this.selectedTemplateIds,
          'user_ids': this.selectedUserIds,
        },
        (response) => {
          this.$refs.spinner.hide();

          this.selected = {};
          this.modifier = 0;

          let priceModifiers = _.map(this.priceModifiers, m => m);

          _.each(response.data.priceModifiers, (priceModifier) => {
            let index = _.findIndex(priceModifiers, { id: priceModifier.id });

            if (index == -1) {
              priceModifiers.push(priceModifier);
            }
            else {
              priceModifiers[index] = priceModifier;
            }
          });

          this.priceModifiers = priceModifiers;
        },
        () => {
          this.$refs.spinner.hide();
        });
    },

    deletePriceModifier(id) {
      this.$refs.spinner.show();
      models.priceModifiers.delete(id,
        (response) => {
          this.$refs.spinner.hide();
          this.priceModifiers = _.filter(this.priceModifiers, (priceModifier) => {
            return priceModifier.id != id;
          });
        },
        () => {
          this.$refs.spinner.hide();
        });
    }
  }

};
</script>

<template>
<div class="pos-r">
  <spinner v-ref:spinner size="md"></spinner>

  <table class="table hovered striped">
    <thead>
      <tr>
        <th>
          <div>
            <multiselect
              :allow-empty="false"
              :close-on-select="true"
              :clear-on-select="true"
              :options="availableOptions"
              :selected.sync="selected"
              :searchable="true"
              :multiple="false"
              option-partial="customOptionPartial"
              select-label=""
              selected-label=""
              :placeholder="'labels.choose' | trans"
              key="id"
              label="name"
              @update="onOptionSelected"
            ></multiselect>
          </div>
        </th>

        <th>
          <input
            type="text"
            class="form-control"
            v-model="modifier"
            />
        </th>

        <th>
          <button
            @click="addPriceModifier"
            type="button"
            class="btn btn-primary"
            :title="'labels.add' | trans">
              <i class="fa fa-plus"></i>
          </button>
        </th>

      </tr>
    </thead>
    <tbody>
      <tr
        v-for="modifier in priceModifiers">
          <td v-if="!isOnUserPage">
            <img class="h-50" :src="modifier.user.photo_url" alt="" />
            <a :href="'/admin/users/'+modifier.user.id+'/edit' | url">
              {{ modifier.user.name }} ({{ modifier.user.email }})
            </a>
          </td>
          <td v-if="isOnUserPage">
            <img class="h-50" :src="modifier.template.image" alt="" />
            <a :href="'/admin/product-models/'+modifier.template.id+'/edit' | url">{{ modifier.template.name }}</a>
          </td>
          <td>{{ modifier.modifier }}%</td>
          <td>
            <button
              @click="deletePriceModifier(modifier.id)"
              type="button"
              class="btn btn-danger"
              :title="'labels.delete' | trans">
              <i class="fa fa-times"></i>
            </button>
          </td>
      </tr>
      <tr v-if="priceModifiers.length < 1">
        <td colspan="3">
          {{ 'labels.no_price_modifiers_so_far' | trans }}
        </td>
      </tr>
    </tbody>
  </table>
</div>
</template>
