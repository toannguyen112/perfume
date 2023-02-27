<template>
  <div class="grid grid-cols-1 gap-32 md:grid-cols-4">
    <template v-for="(feed, index) in feeds">
      <a :key="index" :href="feed.permalink" target="_blank">
        <div
          class="overflow-hidden aspect-w-1 aspect-h-1"
          :class="
            index % 2 ? 'transform md:translate-y-[5rem] translate-y-0' : ''
          "
        >
          <img
            :src="feed.media_url"
            alt="y tuong trang tri cho ngoi nha cua ban"
            class="object-cover w-full h-full overflow-hidden transition duration-300 transform scale-100 hover:scale-105"
          />
        </div>
      </a>
    </template>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: {
    /*
     * Instagram access token.
     */
    token: {
      type: String,
      required: true,
    },
    /*
     * Media Fields (see https://developers.facebook.com/docs/instagram-basic-display-api/reference/media#fields)
     */
    fields: {
      type: String,
      required: true,
    },
    /*
     * Number of posts rendered.
     */
    count: {
      type: Number,
      default: 4,
    },
    /*
     * Kinds of media to filter (Can be IMAGE, VIDEO, or CAROUSEL_ALBUM.).
     */
    mediatypes: {
      type: Array,
      required: true,
    },
    // Class for container div
    containerClass: {
      type: String,
      default: "",
      required: false,
    },
  },
  data: () => ({
    error: null,
    loading: false,
    feeds: [],
  }),
  mounted() {
    this.getUserFeed();
  },
  methods: {
    getUserFeed() {
      this.loading = true;
      axios
        .get("https://graph.instagram.com/me/media", {
          params: { access_token: this.token, fields: this.fields },
        })
        .then((response) => {
          this.loading = false;
          if (response.status === 400) {
            this.error = response.error.message;
          }
          if (response.status === 200) {
            for (const n in response.data.data) {
              if (this.mediatypes.includes(response.data.data[n].media_type)) {
                this.feeds.push(response.data.data[n]);
                if (this.feeds.length >= this.count) {
                  return;
                }
              }
            }
          }
        })
        .catch((error) => {
          throw error;
        });
    },
  },
};
</script>
