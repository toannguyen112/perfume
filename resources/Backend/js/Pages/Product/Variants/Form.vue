<template>
  <FlashMessages />
  <Form :form="form" :rules="rules" name="Sản phẩm">
    <div class="grid grid-cols-12 gap-6">
      <div class="col-span-4">
        <div class="card">
          <Link
            :href="route('sidebar.products.form', { id: product.id })"
            class="flex items-center space-x-2 text-lg cursor-pointer link"
          >
            <Icon name="arrow-right" class="transform rotate-180" />
            {{ product.name }}
          </Link>
        </div>
        <div v-if="product.variants.length" class="table table-inside card">
          <ul>
            <li
              v-for="(variant, index) in product.variants"
              :key="index"
              :class="{
                'bg-gray-100': variant.id == variantId,
              }"
            >
              <Link
                :href="
                  route('sidebar.variants.edit', {
                    productId: variant.product_id,
                    variantId: variant.id,
                  })
                "
                class="block w-full p-2 link"
              >
                <div
                  :class="{
                    'font-bold': variant.id == variantId,
                  }"
                >
                  {{ variant.title || pluck(variant.options, "name").join(" / ") }}
                  <span v-if="variant.is_default">(Mặc định)</span>
                </div>
                <small
                  v-if="variant.options"
                  class="block text-xs text-gray-500"
                >
                  {{ pluck(variant.options, "name").join(" / ") }}
                </small>
              </Link>
            </li>
          </ul>
        </div>
        <div class="card">
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
      </div>
      <div class="col-span-8">
        <div class="space-y-3 card">
          <Fieldset
            v-model="form.title"
            :field="{
              label: 'Tên biến thể',
            }"
          />
          <Fieldset
            v-model="form.thumbnail_url"
            class="w-[100px] h-[100px]"
            :field="{
              type: 'media_upload',
              folder: currentModel(),
              icon: false,
              multiple: false,
              expected: 'url',
            }"
          />
        </div>

        <div class="space-y-3 card">
          <div
            v-if="product.list_variant_option.length == 0"
            class="flex flex-col"
          >
            Hiện tại chưa có thuộc tính nào. Vui lòng thêm thuộc tính vào
            <Link
              :href="route('sidebar.products.form', { id: product.id })"
              class="link"
              >Sản phẩm chính</Link
            >
            trước.
          </div>
          <template
            v-for="(option, index) in product.list_variant_option"
            :key="index"
          >
            <Fieldset
              :model-value="form.options[option.id]"
              :field="{
                rules: 'required',
                label: option.name,
                type: 'select_single',
                nullable: true,
                options: option.nodes,
              }"
              @update:modelValue="
                form.options[option.id] = option.nodes.find(
                  (x) => x.id.toString() === $event.toString()
                )
              "
            />
          </template>
        </div>

        <div class="space-y-3 card">
          <div>
            <label class="label">Album hình</label>
            <div class="relative grid grid-cols-6 gap-2">
              <div
                class="relative col-span-1 group"
                @click="productImagesModal = true"
              >
                <div
                  class="overflow-hidden text-gray-400 transition-colors duration-200 border border-gray-200 border-dashed rounded cursor-pointer select-none hover:border-gray-400 hover:text-gray-600 aspect-square"
                >
                  <div class="flex items-center justify-center h-full">
                    <svg
                      width="22"
                      height="22"
                      viewBox="0 0 22 22"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        d="M1 10h20M12 1v20"
                      ></path>
                    </svg>
                  </div>
                </div>
              </div>
              <template v-if="selectedFiles && selectedFiles.length > 0">
                <div
                  v-for="(file, index) in selectedFiles.sort(
                    (a, b) => b.is_default - a.is_default
                  )"
                  :key="index"
                  class="relative col-span-1 group"
                >
                  <div
                    class="relative overflow-hidden transition-colors duration-200 bg-gray-100 border border-gray-200 rounded cursor-pointer select-none aspect-square group-hover:border-gray-500"
                  >
                    <small
                      class="absolute top-0 w-full text-xs text-center text-black bg-gray-100 h-[1rem] overflow-hidden"
                    >
                      {{ file.image_name }}
                    </small>
                    <img
                      :src="staticUrl(file.image_url)"
                      class="object-contain w-full h-full pointer-events-none"
                    />
                    <div
                      class="absolute invisible transition-all duration-200 opacity-0 right-1 bottom-1 group-hover:opacity-100 group-hover:visible"
                    >
                      <Button
                        size="xxs"
                        @click="
                          removeDefault();
                          file.is_default = 1;
                        "
                      >
                        Mặc định
                      </Button>
                      <Button
                        variant="link-danger"
                        size="xxs"
                        @click="toggleFile(file)"
                      >
                        Xoá
                      </Button>
                    </div>
                  </div>
                </div>
              </template>
            </div>
            <DialogModal
              :show="productImagesModal !== null"
              @close="productImagesModal = null"
            >
              <template #content>
                <div class="grid grid-cols-5 gap-3">
                  <div
                    v-for="(file, index) in product.images"
                    :key="index"
                    class="relative col-span-1 group"
                  >
                    <div
                      class="overflow-hidden transition-colors duration-200 bg-gray-100 border border-gray-100 rounded cursor-pointer select-none aspect-square"
                      :class="{
                        'outline outline-offset-2 outline-2 outline-blue-500':
                          pluck(selectedFiles, 'image_id').includes(
                            file.image_id
                          ),
                      }"
                      @click="toggleFile(file)"
                    >
                      <small
                        class="absolute top-0 w-full text-xs text-center text-black bg-gray-100 h-[1rem] overflow-hidden"
                      >
                        {{ file.image_name }}
                      </small>
                      <img
                        v-if="isImage(file.image_url)"
                        :src="staticUrl(file.image_url)"
                        class="object-contain w-full"
                      />
                    </div>
                  </div>
                </div>
              </template>
              <template #footer>
                <Button
                  variant="white"
                  label="Đóng"
                  size="sm"
                  @click="productImagesModal = null"
                />
              </template>
            </DialogModal>
          </div>
        </div>

        <div class="card">
          <div class="flex space-x-2">
            <Fieldset
              v-model="form.sku"
              :field="{
                label: 'SKU',
              }"
            />
            <Fieldset
              v-model="form.barcode"
              :field="{
                label: 'Barcode',
              }"
            />
          </div>
        </div>

        <div class="card">
          <div class="flex space-x-2">
            <Fieldset
              v-model="form.price"
              :field="{
                label: 'Giá',
                type: 'money',
              }"
            />
            <Fieldset
              v-model="form.old_price"
              :field="{
                label: 'Giá so sánh',
                type: 'money',
              }"
            />
          </div>
        </div>

        <div class="card">
          <Fieldset
            v-model="form.status"
            :field="{
              type: 'radio',
              label: 'Trạng thái',
              options: [
                { id: 1, name: 'Hiển thị' },
                { id: 0, name: 'Ẩn' },
              ],
            }"
          />
        </div>

        <div class="card">
          <Fieldset
            v-model="form.is_default"
            :field="{
              label: 'Đặt làm mặc định',
              type: 'checkbox',
            }"
          />
        </div>

        <div class="card">
          <details>
            <summary class="select-none">
              <span class="">Nguồn sản phẩm</span>
            </summary>
            <div class="mt-3 space-y-3">
              <div>
                <Fieldset
                  :model-value="item.source"
                  disabled
                  :field="{
                    label: 'Nguồn',
                  }"
                />
                <small>
                  <a :href="item.source_url" target="_blank" class="link">{{
                    item.source_url
                  }}</a>
                </small>
              </div>
              <Fieldset
                :model-value="item.source_sku"
                disabled
                :field="{
                  label: 'SKU',
                }"
              />
              <Fieldset
                :model-value="item.source_product_id"
                disabled
                :field="{
                  label: 'ID Sản phẩm',
                }"
              />
              <Fieldset
                :model-value="item.source_variant_id"
                disabled
                :field="{
                  label: 'ID Biến thể',
                }"
              />
              <Fieldset
                :model-value="item.source_category_id"
                disabled
                :field="{
                  label: 'ID Danh mục',
                }"
              />
              <Fieldset
                :model-value="item.source_raw"
                disabled
                :field="{
                  label: 'Raw',
                  type: 'textarea',
                }"
              />
            </div>
          </details>
        </div>

        <div class="flex mt-6">
          <Button label="Lưu" @click="store()" />
          <Button
            v-if="!form.deleted_at && form.id"
            label="Xóa"
            variant="link-danger"
            class="ml-auto"
            @click="destroy()"
          />
        </div>
      </div>
    </div>
  </Form>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated";
import FlashMessages from "@/Components/FlashMessages";
import DialogModal from "@/Components/DialogModal";

export default {
  components: {
    FlashMessages,
    DialogModal,
  },
  layout: Authenticated,
  props: ["product", "item", "rules"],
  remember: "form",
  data() {
    return {
      form: null,
      productImagesModal: null,
      allOptions: [],
      selectedFiles: [],
    };
  },

  computed: {
    placeholder() {
      const options = Object.values(this.form.options);
      return options.map((x) => x.name).join(" / ");
    },
    productId() {
      return this.route().params.productId;
    },
    variantId() {
      return this.route().params.variantId;
    },
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
    removeDefault() {
      this.selectedFiles.map((x) => (x.is_default = false));
    },
    toggleFile(file) {
      const fileIndex = this.pluck(this.selectedFiles, "image_id").indexOf(
        file.image_id
      );
      if (fileIndex < 0) {
        this.selectedFiles.push(file);
      } else {
        this.selectedFiles.splice(fileIndex, 1);
      }
    },
    store() {
      this.form.post(
        this.route(`sidebar.variants.store`, {
          productId: this.productId,
          variantId: this.form.id,
        })
      );
    },

    destroy() {
      if (confirm("Bạn chắc chắn muốn xoá đối tượng này?")) {
        this.$inertia.post(
          this.route(`sidebar.variants.destroy`, { id: this.form.id }),
          {},
          { onSuccess: () => location.reload() }
        );
      }
    },

    initForm() {
      if (this.item.product_images) {
        const variantImage = this.item.product_images.filter(
          (x) => x.variant_ids && x.variant_ids.includes(this.item.id)
        );
        this.selectedFiles = variantImage;
      }
      this.form = this.$inertia.form({
        options: {},
        ...this.item,
        image_ids: this.selectedFiles,
        product_name: this.product.name,
      });
    },
  },
};
</script>
