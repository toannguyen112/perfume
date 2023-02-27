<template>
  <div class="container">
    <Table
      :data="data"
      :filters="filters"
      :columns="columns"
      @search="getData"
      :canCreate="false"
      name="Đơn hàng"
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
        { field: "order_number", label: "Mã" },
        { field: "customer_name", label: "Khách hàng" },
        { field: "formatted_status", label: "Trạng thái" },
        { field: "formatted_payment_method", label: "Phương thức thanh toán" },
        {
          field: "total_price",
          label: "Tồng tiền",
          format: "money",
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
