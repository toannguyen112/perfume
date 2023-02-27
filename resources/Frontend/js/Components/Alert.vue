<template>
  <div
    v-if="show"
    :class="dialogStatus ? 'right-[10px]' : '-right-full'"
    class="bg-white border border-gray-100 rounded alert alert-cart-success"
  >
    <div class="p-1">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <JIcon name="tick_cart" class="mr-1" />
        </div>
        <div class="font-bold text-gray-900">{{ title }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    show: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      dialogStatus: false,
      title: "",
    };
  },
  mounted() {
    const self = this;
    this.$bus.on("open-alert", (data) => {
      self.dialogStatus = true;
      let timeoutID;

      timeoutID = setTimeout(() => {
        self.dialogStatus = false;
        clearTimeout(timeoutID);
      }, 2000);
      self.title = data;
    });
  },
  beforeUnmount() {
    this.$bus.off("open-alert");
  },
};
</script>

<style lang="scss" scoped>
.alert {
  @apply fixed top-[20px] z-[9999] text-white shadow  p-[10px] flex items-center duration-300 ease-in-out transform;
}

.alert-cart-success {
  box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.15);
}
.closebtn {
  margin-left: 15px;
  color: white;
  font-weight: bold;
  float: right;
  line-height: 20px;
  cursor: pointer;
  transition: 0.3s;
}

.closebtn:hover {
  color: black;
}
</style>
