<template>
  <TheHeader />
  <div>
    <slot></slot>
  </div>
  <Alert :show="showAlertSuccessCart" />
  <TheFooter />
</template>
<script>
import axios from "axios";
import TheHeader from "../Components/jam/TheHeader.vue";
import TheFooter from "../Components/jam/TheFooter.vue";
import Alert from "../Components/Alert.vue";
export default {
  components: {
    TheHeader,
    TheFooter,
    Alert,
  },
  data() {
    return {
      showAlertSuccessCart: false,
    };
  },
  created() {
    this.getCart();
  },
  mounted() {
    const self = this;
    if (window) {
      document.addEventListener("scroll", self.scrollToShowAlertCallback);
    }
  },

  beforeUnmount() {
    const self = this;
    document.removeEventListener("scroll", self.scrollToShowAlertCallback);
  },

  methods: {
    getCart() {
      axios.get(this.route(`checkout.cart.index`)).then((res) => {
        this.$bus.emit("updateCart", res.data);
      });
    },
    scrollToShowAlertCallback() {
      let breakPointScrollTop = window.scrollY;
      if (breakPointScrollTop > 100) {
        this.showAlertSuccessCart = true;
      } else {
        this.showAlertSuccessCart = false;
      }
    },
  },
};
</script>
