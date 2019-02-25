let
  Multiselect = require('vue-multiselect').Multiselect;

// changed v-html-text to v-html for optionLabel
Multiselect.template = `
  <div
    tabindex="0"
    :class="{ 'multiselect--active': isOpen, 'multiselect--disabled': disabled }"
    @focus="activate()"
    @blur="searchable ? false : deactivate()"
    @keydown.self.down.prevent="pointerForward()"
    @keydown.self.up.prevent="pointerBackward()"
    @keydown.enter.stop.prevent.self="addPointerElement()"
    @keyup.esc="deactivate()"
    class="multiselect">
      <div @mousedown.prevent="toggle()" class="multiselect__select"></div>
      <div v-el:tags class="multiselect__tags">
        <span v-if="multiple">
          <span
            v-for="option in visibleValue"
            track-by="$index"
            onmousedown="event.preventDefault()"
            class="multiselect__tag">
              <span v-html="getOptionLabel(option)"></span>
              <i
                aria-hidden="true"
                tabindex="1"
                @keydown.enter.prevent="removeElement(option)"
                @mousedown.prevent="removeElement(option)"
                class="multiselect__tag-icon">
              </i>
          </span>
        </span>
        <template v-if="value && value.length > limit">
          <strong v-text="limitText(value.length - limit)"></strong>
        </template>
        <div v-show="loading" transition="multiselect__loading" class="multiselect__spinner"></div>
        <input
          name="search"
          type="text"
          autocomplete="off"
          :placeholder="placeholder"
          v-el:search
          v-if="searchable"
          v-model="search"
          :disabled="disabled"
          @focus.prevent="activate()"
          @blur.prevent="deactivate()"
          @keyup.esc="deactivate()"
          @keyup.down="pointerForward()"
          @keyup.up="pointerBackward()"
          @keydown.enter.stop.prevent.self="addPointerElement()"
          @keydown.delete="removeLastElement()"
          class="multiselect__input"/>
          <span
            v-if="!searchable && !multiple"
            class="multiselect__single"
            v-text="currentOptionLabel || placeholder">
          </span>
      </div>
      <ul
        transition="multiselect"
        :style="{ maxHeight: maxHeight + 'px' }"
        v-el:list
        v-show="isOpen"
        class="multiselect__content">
        <slot name="beforeList"></slot>
        <li v-if="multiple && max === value.length">
          <span class="multiselect__option">
            <slot name="maxElements">Maximum of {{ max }} options selected. First remove a selected option to select another.</slot>
          </span>
        </li>
        <template v-if="!max || value.length < max">
          <li
            v-for="option in filteredOptions"
            track-by="$index"
            tabindex="0"
            :class="{ 'multiselect__option--highlight': $index === pointer && this.showPointer, 'multiselect__option--selected': !isNotSelected(option) }"
            class="multiselect__option"
            @mousedown.prevent="select(option)"
            @mouseenter="pointerSet($index)"
            :data-select="option.isTag ? tagPlaceholder : selectLabel"
            :data-selected="selectedLabel"
            :data-deselect="deselectLabel">
            <partial :name="optionPartial" v-if="optionPartial.length"></partial>
            <span v-else v-html="getOptionLabel(option)"></span>
          </li>
        </template>
        <li v-show="filteredOptions.length === 0 && search">
          <span class="multiselect__option">
            <slot name="noResult">No elements found. Consider changing the search query.</slot>
          </span>
        </li>
        <slot name="afterList"></slot>
    </ul>
  </div>
`;

module.exports = Multiselect;
