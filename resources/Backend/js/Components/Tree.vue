<template>
  <Draggable
    tag="transition-group"
    :component-data="{
      tag: 'ul',
      type: 'transition-group',
    }"
    :model-value="value"
    item-key="id"
    handle=".handle"
    :animation="200"
    group="category"
    :disabled="false"
    ghost-class="ghost"
    class="space-y-2"
    @update:modelValue="$emit('change', $event)"
  >
    <template #item="{ element }">
      <li class="flex-col space-y-2 select-none">
        <div
          class="flex p-2 border rounded-sm cursor-pointer bg-gray-50 handle"
          @click="$parent.$emit('selectedItem', $event)"
        >
          <span class="flex-1">
            {{ element.name }}
          </span>
        </div>
        <TreeViewItemV2
          v-if="currentLevel < 3"
          v-model="element.items"
          :level="currentLevel + 1"
          class="ml-8"
          @change="$emit('change', $event)"
        />
      </li>
    </template>
  </Draggable>
</template>
<script>
import Draggable from "vuedraggable";

export default {
  components: {
    Draggable,
  },
  props: ["value", "field", "level"],
  emits: ["change"],
  computed: {
    currentLevel() {
      return this.level ?? 1;
    },
  },
};
</script>
<style lang="scss">
.ghost {
  @apply opacity-50 border-t;
}
</style>
