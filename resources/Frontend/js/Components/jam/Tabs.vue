<template>
  <div class="tabs">
    <ul class="tabs-header">
      <li
        v-for="(tab, idx) of tabs"
        :key="idx"
        :class="[{ 'is-active': active === tab.props.slug }]"
        class="tab-header-item"
      >
        <a
          :class="[
            active === tab.props.slug ? tabActiveState : tabNormalState,
            tabClass,
          ]"
          href="javascript:;"
          @click="selectTab(tab.props.slug)"
        >
          {{ tab.props.title }}
        </a>
      </li>
    </ul>
    <div class="tabs-detail">
      <slot></slot>
    </div>
  </div>
</template>

<script>
import { provide, computed, ref } from "vue";

export default {
  name: "Tabs",
  props: {
    tabClass: {
      type: String,
      default: "",
    },
    tabActiveState: {
      type: String,
      default: "",
    },
    tabNormalState: {
      type: String,
      default: "",
    },
    modelValue: {
      type: [String, Number],
    },
  },
  emits: ["update:modelValue"],
  setup(props, { slots, emit }) {
    const active = computed(() => props.modelValue);
    const tabs = ref([]);

    function selectTab(tab) {
      emit("update:modelValue", tab);
    }

    provide("tabsState", {
      active,
      tabs,
    });

    return {
      tabs,
      active,
      selectTab,
    };
  },
};
</script>
