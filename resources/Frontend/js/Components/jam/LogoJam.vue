<template>
  <a
    :href="`https://jamstackvietnam.com/?ref=${currentDomain}`"
    target="_blank"
  >
    <JIcon name="made-by-jam" />
  </a>
</template>
<script>
export default {
  data() {
    return {
      currentDomain: null,
    };
  },
  mounted() {
    this.currentDomain = window.location.hostname;
  },
};
</script>
