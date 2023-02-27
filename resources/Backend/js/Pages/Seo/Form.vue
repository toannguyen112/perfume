<template>
  <FormPage
    :form="form"
    :rules="rules"
    name="SEO"
    :form-name="page.meta.meta_title ?? page.meta.name"
  >
    <div class="py-2">
      URL:
      <a
        :href="route('products.index', { slug: route().params.slug })"
        target="_blank"
        class="link"
      >
        {{ route().t.url }}/{{ route().params.slug }}
      </a>
    </div>
    <div class="space-y-6">
      <div class="space-y-3 card">
        <Fieldset
          v-model="form.meta.meta_title"
          :field="{
            rules: 'string|max:170',
            label: 'Meta Title',
          }"
        />
        <Fieldset
          v-model="form.meta.meta_description"
          :field="{
            rules: 'string|max:255',
            label: 'Meta Description',
            type: 'textarea',
          }"
        />
        <Fieldset
          v-model="form.meta.image_url"
          :field="{
            label: 'Hình cover: tỉ lệ 2x1',
            type: 'media_upload',
            folder: currentModel(),
            multiple: false,
            expected: 'url',
          }"
        />
        <Fieldset
          v-model="form.meta.content"
          :field="{
            rules: 'string|max:500',
            label: 'Nội dung',
            type: 'richtext',
            size: 'md',
          }"
        />
      </div>
      <div
        v-if="page.brands.length"
        class="overflow-x-auto bg-white rounded shadow"
      >
        <table class="table">
          <tr class="h-[2.5em]">
            <th>Hãng</th>
            <th>SEO</th>
          </tr>
          <tr
            v-for="(brand, index) in page.brands"
            :key="index"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <div
                class="flex items-center break-words whitespace-pre-wrap cursor-pointer hover:text-primary focus:text-primary-dark"
                @click="goToDetails($event, { brand: brand.slug })"
              >
                {{ brand.name }}
              </div>
            </td>
            <td class="border-t">
              <div
                class="w-4 h-4 badge"
                :class="brand.has_seo ? 'badge-success' : 'badge-danger'"
              ></div>
            </td>
          </tr>
        </table>
      </div>
      <div
        v-if="page.options.length"
        class="overflow-x-auto bg-white rounded shadow"
      >
        <table class="table">
          <tr class="h-[2.5em]">
            <th>Giá trị</th>
            <th>Thuộc tính</th>
            <th>SEO</th>
          </tr>
          <template v-for="(option, index) in page.options">
            <tr
              v-if="!option.active"
              v-for="(childOption, childIndex) in option.children"
              :key="childIndex"
              class="hover:bg-gray-100 focus-within:bg-gray-100"
            >
              <td class="border-t">
                <div
                  class="flex items-center break-words whitespace-pre-wrap cursor-pointer hover:text-primary focus:text-primary-dark"
                  @click="
                    goToDetails($event, {
                      ['opt-' + option.slug]: childOption.id,
                    })
                  "
                >
                  {{ childOption.name }}
                </div>
              </td>
              <td class="border-t">
                <div
                  class="flex items-center break-words whitespace-pre-wrap cursor-pointer hover:text-primary focus:text-primary-dark"
                  @click="
                    goToDetails($event, {
                      ['opt-' + option.slug]: childOption.id,
                    })
                  "
                >
                  {{ option.name }}
                </div>
              </td>
              <td class="border-t">
                <div
                  class="w-4 h-4 badge"
                  :class="
                    childOption.has_seo ? 'badge-success' : 'badge-danger'
                  "
                ></div>
              </td>
            </tr>
          </template>
        </table>
      </div>
    </div>
  </FormPage>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated";
export default {
  layout: Authenticated,
  props: ["page", "rules"],
  remember: "form",
  data() {
    return {
      form: null,
    };
  },

  watch: {
    page() {
      this.initForm();
    },
  },

  created() {
    this.initForm();
  },

  methods: {
    initForm() {
      this.form = this.$inertia.form({
        ...this.page,
      });
    },
    async goToDetails(element, options) {
      let params = this.route().params;
      params = {
        ...params,
        category: this.form.product_category?.slug,
        brand: this.form.brand?.slug,
      };

      const routeFilter = this.route("route-filters", {
        ...params,
        ...options,
        is_admin: true,
      });

      const url = await axios.get(routeFilter).then((res) => res.data);

      window.location = url;
    },
  },
};
</script>
