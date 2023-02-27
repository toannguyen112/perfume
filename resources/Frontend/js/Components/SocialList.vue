<template>
  <div
    class="flex items-center xl:space-x-[24px] md:space-x-[17px] space-x-[12px] text-gray-600 relative z-0"
  >
    <a
      :href="facebookUrl"
      target="_blank"
      class="flex items-center hover:text-brown-600 transition text-gray-600 w-[30px] h-[30px]"
    >
      <JIcon class="text-gray-300" name="fb" />
    </a>
    <div class="h-[30px] rounded-full overflow-hidden">
      <div
        class="zalo-share-button"
        data-href=""
        data-oaid="579745863508352884"
        data-layout="3"
        data-color="blue"
        data-customize="false"
      ></div>
    </div>

    <div class="flex items-center max-md:justify-end">
      <div class="relative hover:opacity-90">
        <div
          @click="copyLink()"
          class="text-gray-300 duration-150 cursor-pointer hover:text-green-500"
        >
          <JIcon name="attach" />
        </div>
        <input id="input-copy" type="hidden" />
        <div
          class="absolute lg:top-[120%] lg:left-[-20%] top-[120%] left-[-200%] bg-red-200 rounded-2xl text-white duration-300 py-[4px] px-[12px] w-max"
          :class="copySuccess ? 'opacity-100' : 'opacity-0'"
        >
          Đã copy link
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      facebookUrl: null,
      copySuccess: false,
    };
  },
  mounted() {
    let Script = document.createElement("script");
    Script.setAttribute("src", "https://sp.zalo.me/plugins/sdk.js");
    document.head.appendChild(Script);
    this.facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${window.location.href}`;
  },
  methods: {
    copyLink() {
      const input = document.querySelector("#input-copy");
      input.value = window.location.href;
      input.setAttribute("type", "text");
      input.select();
      input.setSelectionRange(0, 99999);
      document.execCommand("copy");
      input.setAttribute("type", "hidden");
      window.getSelection().removeAllRanges();
      this.copySuccess = true;
      setTimeout(() => {
        this.copySuccess = false;
      }, 1000);
    },
  },
};
</script>
<style lang="scss" scoped>
::v-deep {
  .zalo-share-button {
    .zb-btn-blue--30x30 {
      border-radius: 100% !important;
    }
  }
}
</style>
