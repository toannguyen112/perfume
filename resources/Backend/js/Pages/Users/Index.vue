<template>
  <div class="container">
    <Table
      :data="data"
      :can-create="false"
      :filters="filters"
      :columns="columns"
      name="Thành viên"
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
        { field: "name", label: "Họ tên" },
        { field: "address_short", label: "Địa điểm" },
        { field: "orders_count", label: "Tổng đơn hàng" },
        { field: "last_order_name", label: "Đơn hàng gần nhất" },
        { field: "total_spent", label: "Tổng" },
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
