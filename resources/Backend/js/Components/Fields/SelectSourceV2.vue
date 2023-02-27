<template>
  <VueMultiselect
    :allow-empty="true"
    :placeholder="placeholder"
    :track-by="keyBy"
    :label="labelBy"
    :options="options"
    :model-value="selectedOptions"
    :loading="loading"
    :close-on-select="!isMultiple"
    :clear-on-select="false"
    :preselect-first="false"
    :searchable="true"
    :multiple="isMultiple"
    :taggable="taggable"
    :internal-search="!lazySearch"
    @update:model-value="selectChange"
    @tag="addOption"
    @search-change="asyncSearch"
  />
</template>

<script>
import VueMultiselect from "vue-multiselect";
export default {
  components: {
    VueMultiselect,
  },

  props: ["value", "field"],
  emits: ["change"],

  data() {
    return {
      loading: false,
      options: [],
      timer: null,
    };
  },

  computed: {
    keyBy() {
      return this.field.keyBy || "id";
    },
    labelBy() {
      return this.field.labelBy || "label";
    },
    isMultiple() {
      return this.field.multiple === undefined ? true : this.field.multiple;
    },
    taggable() {
      return this.field.taggable === undefined ? false : this.field.taggable;
    },
    placeholder() {
      return this.field.placeholder || "Vui lòng chọn";
    },
    sourceIsConst() {
      return (
        this.field.source !== undefined && this.field.source.const !== undefined
      );
    },
    lazySearch() {
      return (
        this.field.source !== undefined &&
        this.field.source.method === "lazySearch"
      );
    },
    selectedOptions() {
      if (
        this.value === null ||
        this.value === undefined ||
        this.value.length === 0
      )
        return [];

      if (!this.isMultiple) {
        return this.options.find((x) => x[this.keyBy] == this.value);
      }

      let defaultValue = [];

      if (this.value.length > 0 && typeof this.value[0] === "object") {
        defaultValue = this.value.map((x) => x[this.keyBy].toString());
      } else {
        defaultValue = this.value.map((x) => x.toString());
      }

      return this.options.filter((x) =>
        defaultValue.includes(x[this.keyBy].toString())
      );
    },
  },

  watch: {
    "field.options": {
      handler() {
        this.fetchSource();
      },
    },
    value: {
      handler() {
        if (this.lazySearch && this.value) {
          this.fetchSource({ params: { keyword: this.value } });
        }
      },
    },
  },

  created() {
    if (!this.lazySearch) {
      this.fetchSource();
    } else if (this.lazySearch && this.value) {
      this.fetchSource({ params: { keyword: this.value } });
    }
  },

  methods: {
    async fetchSource(appendParams = {}) {
      let options = [];
      if (this.field.options !== undefined) {
        options = this.field.options;
      } else {
        this.loading = true;
        await axios
          .post(route("helper.model-data"), {
            ...this.field.source,
            ...appendParams,
          })
          .then((res) => {
            options = res.data;

            if (this.sourceIsConst) {
              options = Object.entries(options).map((x) => {
                return { [this.keyBy]: x[0], [this.labelBy]: x[1] };
              });
            }

            this.loading = false;
          });
      }

      if (this.field.emptyLabel !== undefined && options.length > 0) {
        let emptyOption = { ...options[0] };
        Object.keys(emptyOption).forEach((key) => {
          emptyOption[key] = key === this.labelBy ? this.field.emptyLabel : 0;
        });
        options.unshift(emptyOption);
      }

      this.options = options;
    },
    selectChange(selected) {
      if (!this.isMultiple) {
        this.$emit("change", selected[this.keyBy]);
      } else {
        this.$emit("change", selected);
      }
    },
    addOption(newValue) {
      let selectedOptions = this.selectedOptions;
      selectedOptions.push(newValue);
      this.selectChange(selectedOptions);
    },
    asyncSearch(keyword) {
      if (keyword.length === 0) return;

      if (this.timer) {
        clearTimeout(this.timer);
        this.timer = null;
      }

      this.timer = setTimeout(() => {
        this.loading = true;
        this.fetchSource({ params: { keyword } });
      }, 150);
    },
  },
};
</script>
