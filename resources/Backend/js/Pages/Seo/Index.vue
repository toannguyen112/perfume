<template>
  <div class="overflow-x-auto bg-white rounded shadow">
    <table class="table">
      <tr class="h-[2.5em]">
        <th>Tên</th>
        <th>Loại</th>
        <th>ID</th>
        <th class="px-6 pt-6 pb-4"></th>
      </tr>
      <tr
        v-for="item in categories"
        :key="item.id"
        class="hover:bg-gray-100 focus-within:bg-gray-100"
      >
        <td class="border-t">
          <Link
            class="flex items-center break-words whitespace-pre-wrap hover:text-primary focus:text-primary-dark"
            :href="
              route(`sidebar.seo.form`, {
                slug: item.custom_slug ?? item.slug,
              })
            "
          >
            {{ item.name }}
          </Link>
        </td>
        <td class="border-t">
          <Link
            class="flex items-center break-words whitespace-pre-wrap hover:text-primary focus:text-primary-dark"
            :href="
              route(`sidebar.seo.form`, {
                slug: item.custom_slug ?? item.slug,
              })
            "
          >
            Danh mục
          </Link>
        </td>
        <td class="border-t">
          <Link
            class="flex items-center break-words whitespace-pre-wrap hover:text-primary focus:text-primary-dark"
            :href="
              route(`sidebar.seo.form`, {
                slug: item.custom_slug ?? item.slug,
              })
            "
          >
            {{ item.id }}
          </Link>
        </td>
        <td class="w-px border-t">
          <Link
            class="flex items-center px-4"
            :href="
              route(`sidebar.seo.form`, {
                slug: item.custom_slug ?? item.slug,
              })
            "
            tabindex="-1"
          >
            <Icon name="arrow-right" class="block w-6 h-6 text-gray-400" />
          </Link>
        </td>
      </tr>
      <tr
        v-for="item in brands"
        :key="item.id"
        class="hover:bg-gray-100 focus-within:bg-gray-100"
      >
        <td class="border-t">
          <Link
            class="flex items-center break-words whitespace-pre-wrap hover:text-primary focus:text-primary-dark"
            :href="
              route(`sidebar.seo.form`, {
                slug: item.custom_slug ?? item.slug,
              })
            "
          >
            {{ item.name }}
          </Link>
        </td>
        <td class="border-t">
          <Link
            class="flex items-center break-words whitespace-pre-wrap hover:text-primary focus:text-primary-dark"
            :href="
              route(`sidebar.seo.form`, {
                slug: item.custom_slug ?? item.slug,
              })
            "
          >
            Thương hiệu
          </Link>
        </td>
        <td class="border-t">
          <Link
            class="flex items-center break-words whitespace-pre-wrap hover:text-primary focus:text-primary-dark"
            :href="
              route(`sidebar.seo.form`, {
                slug: item.custom_slug ?? item.slug,
              })
            "
          >
            {{ item.id }}
          </Link>
        </td>

        <td class="w-px border-t">
          <Link
            class="flex items-center px-4"
            :href="
              route(`sidebar.seo.form`, {
                slug: item.custom_slug ?? item.slug,
              })
            "
            tabindex="-1"
          >
            <Icon name="arrow-right" class="block w-6 h-6 text-gray-400" />
          </Link>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated";
export default {
  layout: Authenticated,
  props: ["categories", "brands"],

  data() {
    return {
      data: {},
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
