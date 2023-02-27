<template>
  <FormPage
    :form="form"
    :rules="rules"
    name="Đơn hàng"
    :name="item.order_number"
  >
    <div class="space-y-3 card">
      <Fieldset
        disabled
        :model-value="item.order_number"
        :field="{
          label: 'Order ID',
        }"
      />
      <Fieldset
        disabled
        :model-value="item.formatted_created_at"
        :field="{
          label: 'Ngày đặt',
        }"
      />
      <Fieldset
        disabled
        :model-value="item.formatted_payment_method"
        :field="{
          label: 'Phương thức thanh toán',
        }"
      />
    </div>
    <div class="space-y-3 card">
      <label class="heading">Chi tiết đơn hàng</label>
      <table class="table">
        <tbody>
          <tr>
            <td>Tên sản phẩm</td>
            <td class="text-right">Giá</td>
            <td class="text-right">Số lượng</td>
            <td class="text-right">Tổng</td>
          </tr>
          <tr v-for="(product, key) in item.order_lines">
            <td>
              <Link
                :href="
                  route('sidebar.variants.edit', {
                    productId: product.id,
                    variantId: product.variant_id,
                  })
                "
                target="_blank"
                class="link"
                >{{ product.name ?? product.title ?? item.name }}</Link
              >
              <div class="text-xs text-gray-600">SKU: {{ product.sku }}</div>
              <div class="text-xs text-gray-600">
                {{ product.variant_title }}
              </div>
            </td>
            <td class="text-right">{{ toMoney(product.price) }}</td>
            <td class="text-right">{{ product.quantity }}</td>
            <td class="text-right">
              {{ toMoney(product.price * product.quantity) }}
            </td>
          </tr>
          <tr class="font-semibold">
            <td colspan="4" class="text-right">
              Tiền vận chuyển: {{ toMoney(item.total_shipping) }}
            </td>
          </tr>
          <tr class="font-semibold">
            <td colspan="4" class="text-right">
              Tổng tiền: {{ toMoney(item.total_price) }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="space-y-6 card">
      <label class="label">Khách hàng</label>
      <Fieldset
        disabled
        :model-value="item.customer_name"
        :field="{
          label: 'Tên',
        }"
      />
      <Fieldset
        disabled
        :model-value="item.email ?? '-'"
        :field="{
          label: 'Email',
        }"
      />
      <Fieldset
        disabled
        :model-value="item.phone ?? '-'"
        :field="{
          label: 'Số điện thoại',
        }"
      />
      <Fieldset
        disabled
        :model-value="item.city ?? '-'"
        :field="{
          label: 'Tỉnh/Thành phố',
        }"
      />
      <Fieldset
        disabled
        :model-value="item.district ?? '-'"
        :field="{
          label: 'Quận/Huyện',
        }"
      />
      <Fieldset
        disabled
        :model-value="item.ward ?? '-'"
        :field="{
          label: 'Phường/Xã',
        }"
      />
      <Fieldset
        disabled
        :model-value="item.shipping_address ?? '-'"
        :field="{
          label: 'Địa chỉ giao hàng',
        }"
      />
    </div>

    <template #right>
      <div class="card">
        <Fieldset
          disabled
          :model-value="item.note"
          :field="{
            type: 'textarea',
            label: 'Ghi chú đơn hàng',
          }"
        />
      </div>
      <div class="card">
        <Fieldset
          v-model="form.status"
          :field="{
            rules: rules['status'],
            type: 'radio',
            label: 'Trạng thái',
            options: [
              { id: 1, name: 'Trạng thái mới' },
              { id: 3, name: 'Xác nhận' },
              { id: 5, name: 'Đang xử lý' },
              { id: 7, name: 'Đang giao hàng' },
              { id: 9, name: 'Đã giao hàng' },
              { id: 11, name: 'Hoàn thành' },
              { id: 13, name: 'Hủy' },
            ],
          }"
        />
      </div>
    </template>
  </FormPage>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated";

export default {
  layout: Authenticated,
  props: ["item", "rules"],
  remember: "form",
  data() {
    return {
      form: null,
    };
  },

  watch: {
    item(value) {
      value.id && this.initForm();
    },
  },

  created() {
    this.initForm();
  },

  methods: {
    initForm() {
      this.form = this.$inertia.form({ ...this.item });
    },
  },
};
</script>
