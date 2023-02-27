<template>
  <Head title="Sản phẩm" />
  <div class="container">
    <Table
      :canCreate="false"
      :canExport="true"
      :data="data"
      :filters="filters"
      :columns="columns"
      @search="getData"
      name="Sản phẩm"
      idBy="id"
    >
      <template #actions="{ selectedRows }">
        <Button
          href="/"
          label="Merge"
          size="sm"
          @click="mergeProduct(selectedRows)"
        />
      </template>
      <template #filter>
        <Fieldset
          class="w-[250px]"
          :modelValue="filters.category_ids"
          @update:modelValue="
            filters.category_ids = $event;
            onChange(pluck($event, 'id').join(','), 'category_ids');
          "
          :field="{
            type: 'select_tree',
            placeholder: 'Danh mục',
            labelBy: 'name',
            keyBy: 'id',
            returnKey: true,
            childrenBy: 'nodes',
            source: {
              model: 'Product\\ProductCategory',
              method: 'getRoot',
              only: ['id', 'name', 'nodes'],
            },
          }"
        />
        <Dropdown class="w-[150px]" align="left">
          <template #trigger>
            <div class="relative flex cursor-pointer">
              <input
                type="text"
                class="w-full rounded-none cursor-pointer input"
                readonly
                value="Trạng thái"
              />
              <div
                class="absolute flex items-center justify-center w-6 h-6 text-xs font-medium text-white transform -translate-y-1/2 bg-blue-500 rounded-full  right-2 top-1/2"
              >
                {{
                  Object.values(filters.statuses).filter((x) => x === true)
                    .length
                }}
              </div>
            </div>
          </template>
          <template #content>
            <div class="px-3">
              <Fieldset
                :modelValue="filters.statuses.active"
                @update:modelValue="
                  filters.statuses.active = $event;
                  onChange();
                "
                :field="{
                  label: 'Hiển thị',
                  type: 'checkbox',
                }"
              />
              <Fieldset
                :modelValue="filters.statuses.disable"
                @update:modelValue="
                  filters.statuses.disable = $event;
                  onChange();
                "
                :field="{
                  label: 'Ẩn',
                  type: 'checkbox',
                }"
              />
              <Fieldset
                :modelValue="filters.statuses.deleted"
                @update:modelValue="
                  filters.statuses.deleted = $event;
                  onChange();
                "
                :field="{
                  label: 'Đã xóa',
                  type: 'checkbox',
                }"
              />
              <hr />
              <Fieldset
                :modelValue="filters.statuses.source_is_merge"
                @update:modelValue="
                  filters.statuses.source_is_merge = $event;
                  onChange();
                "
                :field="{
                  label: 'Đã Merge',
                  type: 'checkbox',
                }"
              />
            </div>
          </template>
        </Dropdown>
      </template>
    </Table>
  </div>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated";
const defaultStatuses = {
  active: true,
  disable: false,
  deleted: false,
  source_is_merge: false,
};
export default {
  layout: Authenticated,

  data() {
    return {
      columns: [
        { field: "name", label: "Tên" },
        {
          field: "image_url",
          label: "Hình ảnh",
          type: "image"
        },
        {
          field: "categories",
          label: "Danh mục",
          format: function (value) {
            if (value) {
              return value.map((x) => x.name).join("\n");
            }
            return "Chưa cập nhật";
          },
        },
        {
          field: "status",
          label: "Trạng thái",
          format: function (value) {
            return value === 1 ? "Hiển thị" : "Ẩn";
          },
        },
        {
          field: "variants",
          label: "Phiên bản",
          format: function (value) {
            return `${value.length} phiên bản`;
          },
        },
        { field: "formatted_updated_at", label: "Cập nhật cuối" },
      ],
      data: {},
      filters: {
        search: null,
        category_ids: route().params.category_ids
          ? [...route().params.category_ids.split(",")]
          : [],
        statuses: defaultStatuses,
      },
    };
  },

  created() {
    this.getData();
  },

  methods: {
    onChange(value, key = null) {
      let params = { ...route().params };

      if (key !== null) {
        params = {
          ...params,
          [key]: value,
        };
      }

      if (params.search === "") {
        delete params.search;
      } else if (params.page) {
        delete params.page;
      }

      params = { ...params, statuses: this.filters.statuses };

      axios
        .get(route(`sidebar.${this.currentModel()}.index`, params))
        .then((res) => {
          this.data = res.data;
        });
    },

    getData() {
      const params = { ...route().params, statuses: this.filters.statuses };
      axios
        .get(route(`sidebar.${this.currentModel()}.index`, params))
        .then((res) => {
          this.data = res.data;
        });
    },

    mergeProduct(ids) {
      this.$inertia.form({ ids }).post(route("sidebar.products.merge"), {
        onSuccess: () => this.getData(),
      });
    },
  },
};
</script>
