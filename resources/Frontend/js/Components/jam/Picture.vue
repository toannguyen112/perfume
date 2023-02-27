<template>
  <img :src="src" :alt="alt" :loading="loading" :sizes="setSize" />
</template>

<script>
export default {
  props: {
    src: {
      type: String,
      default: "",
    },
    alt: {
      type: String,
      default: "",
    },
    loading: {
      type: String,
      default: "lazy",
    },
    width: {
      type: Number,
    },
    height: {
      type: Number,
    },
    sizes: {
      type: String,
    },
    col: {
      type: String,
    },
  },

  computed: {
    setSize() {
      if (this.sizes) return sizes;
      return {
        full: "sm:100vw md:100%",
        1: "sm:100vw md:100vw lg:100vw xl:100vw 2xl:100vw 3xl:100vw",
        2: "sm:100vw md:50vw lg:60vw xl:60vw 2xl:60vw 3xl:60vw",
        3: "sm:100vw md:100vw lg:40vw xl:40vw 2xl:40vw 3xl:40vw",
        4: "sm:100vw md:70vw lg:30vw xl:30vw 2xl:30vw 3xl:30vw",
      }[this.col];
    },
  },
};
</script>

<style lang="scss">
.picture-cover {
  @apply flex w-full h-full;
  img {
    @apply object-cover w-full h-full;
  }
}
</style>
