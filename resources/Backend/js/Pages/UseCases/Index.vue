<template>
  <div class="container">
    <Table
      :data="data"
      :filters="filters"
      :columns="columns"
      name="Cách sử dụng"
      @search="getData"
    />
  </div>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated";
export default {
  layout: Authenticated,

  data() {
    return {
      columns: [
        { field: "name", label: "Tên" },
        {
          field: "status",
          label: "Trạng thái",
          format: function (value) {
            return value === 1 ? "Hoạt dộng" : "Ẩn";
          },
        },
        { field: "formatted_updated_at", label: "Ngày cập nhật" },
        { field: "formatted_created_at", label: "Ngày tạo" },
      ],
      data: {},
      filters: {
        search: null,
      },
    };
  },

  created() {
    this.getData();
  },

  methods: {
    getData() {
      axios
        .get(route(`sidebar.${this.currentModel()}.index`, route().params))
        .then((res) => {
          this.data = res.data;
        });
    },
  },
};
</script>
