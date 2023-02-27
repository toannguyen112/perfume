<template>
  <div class="container">
    <Table :canCreate="false" :data="data" :filters="filters" :columns="columns" @search="getData" name="Tuyển dụng" />
  </div>
</template>

<script>
import Authenticated from '@/Layouts/Authenticated';
export default {
  layout: Authenticated,
  data() {
    return {
      columns: [
        {
          field: "data",
          label: "Email",
          format(data) {
            return data["Email"];
          },
        },
        {
          field: "data",
          label: "Họ và tên",
          format(data) {
            return data["Họ và tên"];
          },
        },
        {
          field: "data",
          label: "Số điện thoại",
          format(data) {
            return data["Số điện thoại"];
          },
        },
        { field: "type" },
        { field: "status" },
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
        .get(
          route(`sidebar.${this.currentModel()}.index`, route().params, {
            params: {
              type: "CONTACT",
            },
          })
        )
        .then((res) => {
          this.data = res.data;
        });
    },
  },
};
</script>
