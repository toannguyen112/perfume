<template>
  <div>
    <div
      class="relative overflow-y-auto scroll-bar"
      :class="[showContent ? 'max-h-none' : contentClass]"
      :id="contentId"
    >
      <div
        class="absolute bottom-0 left-0 w-full h-full max-h-20 transparent-img bg-gradient-to-t from-white"
        v-if="hasOverlay && !showContent"
      ></div>
      <slot></slot>
    </div>
    <div v-if="hasOverlay" :class="wrapBtnClass">
      <JButton
        :title="showContent ? collapsedText : expandedText"
        :size="btnSize"
        :variant="btnVariant"
        @click="showMore"
        v-if="isButton"
      />
      <a
        v-else
        href="javascript:;"
        :class="[
          customClass,
          { active: showContent },
          { 'space-x-2 flex items-center': hasIcon },
        ]"
        @click="showMore"
      >
        <span class="relative z-10 text">{{
          `${showContent ? collapsedText : expandedText}`
        }}</span>
        <JIcon
          name="arrow-down-sm"
          v-if="hasIcon"
          :class="[showContent ? 'rotate-180' : '']"
        />
        <!-- <span v-if="hasIcon" class="icon" :class="[showContent ? 'icon-up' : 'icon-down']"></span> -->
      </a>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    btnTitle: "",
    btnSize: "",
    btnVariant: "",
    isButton: false,
    wrapBtnClass: "",
    customClass: "",
    collapsedText: "",
    expandedText: "",
    hasIcon: false,
    hasOverlay: false,
    contentClass: "",
    contentId: "",
  },
  methods: {
    showMore() {
      let wrapTag = document.getElementById(this.contentId);
      this.showContent = !this.showContent;
      if (!!this.contentId && wrapTag.hasAttribute("data-max-height")) {
        if (this.showContent) {
          wrapTag.style.maxHeight = "none";
        } else {
          wrapTag.style.maxHeight = wrapTag.getAttribute("data-max-height");
        }
      }
    },
  },
  data() {
    return {
      showContent: false,
    };
  },
};
</script>

<!-- <style lang="scss" scoped>
.scroll-bar {
  -ms-overflow-style: none; /* IE and Edge */
  scrollbar-width: none; /* Firefox */
}
</style> -->
