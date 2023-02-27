<template>
  <Head>
    <title>{{ titleCapitalize }}</title>
    <meta name="description" :content="description" />

    <meta property="og:title" :content="title" />
    <meta property="og:description" :content="description" />
    <meta property="og:type" content="website" />
    <meta property="og:url" :content="site.BASE_URL" />
    <meta property="og:image" :content="image" />
    <meta property="og:image:secure_url" :content="image" />
    <meta property="og:image:alt" :content="titleCapitalize" />
    <meta property="og:locale" content="vi_VN" />
    <meta property="og:site_name" :content="site.SITE_NAME" />

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" :content="titleCapitalize" />
    <meta name="twitter:description" :content="description" />
    <meta name="twitter:image" :content="image" />
    <meta name="twitter:image:alt" :content="titleCapitalize" />
    <meta name="twitter:domain" :content="site.BASE_URL" />
    <meta name="twitter:url" :content="site.BASE_URL" />
  </Head>
</template>

<script>
import site from "../../js/site.json";
import { Head } from "@inertiajs/inertia-vue3";
// Test on: https://cards-dev.twitter.com/validator
// Test on: https://developers.facebook.com/tools/debug/
export default {
  components: { Head },
  props: {
    title: {
      type: String,
      required: true,
    },
    description: {
      type: String,
      default: site.SITE_DESCRIPTION,
      required: false,
    },
    image: {
      type: String,
      default: site.BASE_URL + site.SITE_COVER,
    },
    capitalize: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      site: site,
    };
  },
  computed: {
    titleCapitalize() {
      return this.capitalize ? this.capitalizeText(this.title) : this.title;
    },

    imageSocial() {
      return this.image ? this.image : site.BASE_URL + site.SITE_COVER;
    },
  },
  methods: {
    capitalizeText(string) {
      return string
        ? string
            .toUpperCase()
            .replace(/(?:^|\s)\S/g, function (a) {
              return a.toUpperCase();
            })
            .replace("Jamstack", "JAMstack")
        : "";
    },
  },
};
</script>
