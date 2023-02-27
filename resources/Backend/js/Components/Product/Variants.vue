<template>
  <div class="space-y-3 card">
    <label class="label">Biến thể</label>
    <table class="table table-inside">
      <tr v-for="(variant, variantIndex) in variants" class="p-2">
        <td class="flex flex-col">
          <Link
            :href="
              route('sidebar.variants.edit', {
                productId: variant.product_id,
                variantId: variant.id,
              })
            "
            class="text-base link"
          >
            {{ variant.title || pluck(variant.options, "name").join(" / ") }}
            <span v-if="variant.is_default"> (Mặc định)</span>
          </Link>
          <small>ID: {{ variant.id }} </small>
          <small v-if="variant.sku">SKU: {{ variant.sku }} </small>
        </td>
        <td class="text-xs">
          Giá: {{ toMoney(variant.price) }}<br />
          Giá so sánh: {{ toMoney(variant.old_price) }}<br />
        </td>
        <td class="text-xs">
          {{ pluck(variant.options, "name").join(" / ") }}
        </td>
        <td class="text-xs">
          <Button
            variant="link-danger"
            label="Xóa"
            @click="destroyVariant(variant.id)"
          />
        </td>
      </tr>
    </table>

    <Button
      :href="
        route('sidebar.variants.form', {
          productId: productId,
        })
      "
      label="Thêm mới"
      variant="outline-secondary"
    />
  </div>
</template>
<script>
import DialogModal from "@/Components/DialogModal";
export default {
  components: {
    DialogModal,
  },
  props: ["variants", "options", "allOptions"],
  emits: ["update:variants"],
  data() {
    return {
      values: [],
      variantModal: null,
      variantOptions: [],
    };
  },

  computed: {
    productId() {
      return this.route().params.id;
    },
  },

  created() {
    this.variants.map(function (variant) {
      variant.options = variant.options.length === 0 ? {} : variant.options;
      return variant;
    });
  },

  methods: {
    destroyVariant(id) {
      if (confirm("Bạn chắc chắn muốn xoá đối tượng này?")) {
        this.$inertia.post(
          this.route(`sidebar.variants.destroy`, {
            id: id,
          })
        );
      }
    },

    getChildNodes(parent) {
      if (!parent) return [];

      const parentId = parent.id;
      const option = this.options.find((x) => x.id === parentId);
      return option ? option.nodes : [];
    },

    combinations(args) {
      var r = [],
        max = args.length - 1;
      function helper(arr, i) {
        for (var j = 0, l = args[i].length; j < l; j++) {
          var a = arr.slice(0);
          a.push(args[i][j]);
          if (i == max) r.push(a);
          else helper(a, i + 1);
        }
      }
      helper([], 0);
      return r;
    },

    findOption(id, isChild = false) {
      if (!isChild) {
        return this.allOptions.find((x) => x.id === id);
      } else {
        for (const option of this.allOptions) {
          const child = option.nodes.find((item) => item.id === id);
          if (child) return child;
        }
      }
    },

    optionName(option) {
      const findOption = this.findOption(option.id);
      return findOption ? findOption.name : null;
    },
  },
};
</script>
