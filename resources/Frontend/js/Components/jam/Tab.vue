<template>
  <div class="tab-content" v-if="isActive">
    <slot></slot>
  </div>
</template>

<script>
import { computed, inject, watchEffect, getCurrentInstance } from "vue";

export default {
  name: "Tab",
  props: {
    title: String,
    slug: String,
  },
  setup(props) {
    const instance = getCurrentInstance();
    const { tabs, active } = inject("tabsState");

    const index = computed(() =>
      tabs.value.findIndex((target) => target.uid === instance.uid)
    );

    const isActive = computed(() => props.slug === active.value);

    watchEffect(() => {
      if (index.value === -1) {
        tabs.value.push(instance);
      }
    });

    return {
      isActive,
    };
  },
};
</script>
