<template>
  <FormPage :form="form" :rules="rules" name="Thuộc tính">
    <div class="space-y-6 card">
      <Fieldset
        v-model="form.name"
        :field="{
          rules: rules['name'],
          label: 'Tên',
        }"
      />

      <Fieldset
        v-model="form.position"
        :field="{
          rules: rules['position'],
          label: 'Thứ tự',
        }"
      />

      <Fieldset
        v-model="form.display_position"
        :field="{
          rules: rules['display_position'],
          type: 'radio',
          label: 'Ví trị hiển thị',
          options: [
            { id: 3, name: 'Nốt hương' },
            { id: 2, name: 'Đặc tính nổi bật' },
            { id: 1, name: 'Thông số sản phẩm' },
          ],
        }"
      />

      <Fieldset
        v-model="form.display_type"
        :field="{
          rules: rules['display_type'],
          type: 'radio',
          label: 'Kiểu hiển thị',
          options: [
            { id: 1, name: 'Chữ & hình' },
            { id: 2, name: 'Chữ' },
            { id: 3, name: 'Hình' },
          ],
        }"
      />
    </div>

    <div class="card">
      <div class="-mx-2">
        <Draggable
          tag="transition-group"
          :component-data="{
            tag: 'ul',
            type: 'transition-group',
          }"
          v-model="form.children"
          item-key="id"
          handle=".handle"
          :animation="200"
        >
          <template #item="{ index, element }">
            <div
              class="flex flex-grow p-2 space-x-2 bg-white rounded"
              :key="index"
            >
              <div class="flex flex-col justify-between">
                <div class="p-2 border rounded handle">
                  <Icon name="hamburger" />
                </div>
              </div>
              <div class="flex flex-grow space-x-2">
                <Fieldset
                  class="w-[100px] h-[100px]"
                  v-model="element.image_url"
                  :field="{
                    type: 'media_upload',
                    folder: currentModel(),
                    icon: false,
                    multiple: false,
                    expected: 'url',
                  }"
                />
                <div class="flex-1">
                  <Fieldset
                    v-model="element.name"
                    :field="{
                      placeholder: 'Nhập nhãn',
                    }"
                  />
                  <Button
                    @click="form.children.splice(index, 1)"
                    size="sm"
                    variant="link-danger"
                    class="mt-2 opacity-60 hover:opacity-100"
                    >Xóa</Button
                  >
                </div>
              </div>
            </div>
          </template>
        </Draggable>
        <div class="flex p-2 space-x-2 bg-white rounded">
          <Button
            label="Thêm mới"
            variant="outline-secondary"
            @click="addValue"
          />
        </div>
      </div>
    </div>

    <template #right>
      <div class="card">
        <Fieldset
          v-model="form.status"
          :field="{
            rules: rules['status'],
            type: 'radio',
            label: 'Trạng thái',
            options: [
              { id: 1, name: 'Hiển thị' },
              { id: 2, name: 'Ẩn' },
            ],
          }"
        />
      </div>
      <div class="card">
        <Fieldset
          v-model="form.categories"
          :field="{
            type: 'select_source',
            label: 'Danh mục',
            labelBy: 'name',
            source: {
              model: 'Product\\ProductCategory',
              method: 'getRootProduct',
            },
            multiple: true,
          }"
        />
      </div>
    </template>
  </FormPage>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated";
import Draggable from "vuedraggable";

export default {
  layout: Authenticated,
  components: { Draggable },
  props: ["item", "rules"],
  remember: "form",
  data() {
    return {
      form: null,
    };
  },

  created() {
    this.initForm();
  },

  watch: {
    item(value) {
      value.id && this.initForm();
    },
  },

  methods: {
    addValue() {
      this.form.children.push({
        id: this.form.id,
        name: "",
      });
    },
    initForm() {
      const children = this.item.children || [];

      this.item.parent_id = this.item.parent_id ? this.item.parent_id : 0;
      this.form = this.$inertia.form({ ...this.item, children });
    },
  },
};
</script>

<style lang="scss" scoped>
.sortable-ghost {
  @apply opacity-0;
}
.handle {
  cursor: grab;
}
:deep(.sortable-chosen),
:deep(.sortable-drag) {
  .handle {
    cursor: grabbing;
  }
}
</style>
