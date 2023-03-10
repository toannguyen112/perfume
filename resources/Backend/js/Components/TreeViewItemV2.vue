<template>
  <Draggable
    tag="ul"
    :model-value="items"
    item-key="hash_id"
    handle=".handle"
    :animation="200"
    group="category"
    :disabled="!draggable"
    ghost-class="ghost"
    class="tree-view-list"
    @update:modelValue="$emit('update:modelValue', $event)"
  >
    <template #item="{ element }">
      <li class="flex-col select-none tree-view-item">
        <div
          class="flex cursor-pointer hover:bg-blue-50"
          :class="{
            'text-blue-500':
              selectable &&
              modelValue !== undefined &&
              modelValue.some((x) => x[keyBy] === element[keyBy]),
          }"
        >
          <div
            class="flex items-center w-full px-1 group handle"
            @click="onClickItem(element)"
          >
            <Icon
              name="arrow-right"
              class="transform"
              :class="{
                'rotate-90': elementIsActive(element),
                'opacity-20':
                  !element[childrenBy] || element[childrenBy].length === 0,
              }"
              @click="onClickIcon(element)"
            />
            <label class="flex-1 py-1 space-x-2" @click="onClickName(element)">
              {{ element[labelBy] }}
              <span v-if="badgeBy" class="badge badge-info">{{
                element[badgeBy]
              }}</span>
            </label>
          </div>
        </div>
        <TreeViewItemV2
          v-show="currentLevel < maxLevel"
          :key="element.hash_id"
          v-model="modelValue"
          v-model:options="element[childrenBy]"
          :level="currentLevel + 1"
          class="tree-view-list"
          :class="[
            {
              'max-h-0 overflow-hidden': !elementIsActive(element),
            },
            selectable ? 'ml-4' : 'ml-8',
          ]"
          :active="!elementIsActive(element)"
          :field="field"
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
  props: ["modelValue", "field", "level", "active", "options"],
  emits: ["update:modelValue", "change", "update:options"],
  data() {
    return {
      items: [],
    };
  },
  computed: {
    keyBy() {
      return this.field.keyBy || "id";
    },
    labelBy() {
      return this.field.labelBy || "label";
    },
    badgeBy() {
      return this.field.badgeBy || false;
    },
    childrenBy() {
      return this.field.childrenBy ?? "children";
    },
    currentLevel() {
      return this.level === undefined ? 1 : this.level;
    },
    currentLevelIsActive() {
      if (this.active !== undefined) {
        return this.active;
      } else if (this.currentLevel <= this.expandDefaultLevel) {
        return true;
      } else {
        return false;
      }
    },
    maxLevel() {
      return this.field.maxLevel ?? 3;
    },
    expandDefaultLevel() {
      return this.field.expandDefaultLevel ?? 1;
    },
    draggable() {
      return this.field.draggable ?? false;
    },
    selectable() {
      return this.field.selectable ?? false;
    },
  },

  watch: {
    "field.options"() {
      this.fetchSource();
    },
  },

  created() {
    this.fetchSource();
  },

  methods: {
    fetchSource() {
      if (this.currentLevel === 1) {
        if (this.field.options !== undefined) {
          this.items = this.field.options;
        } else {
          axios
            .post(route("helper.model-data"), this.field.source)
            .then((res) => {
              this.items = res.data;
            });
        }
      } else {
        this.items = this.options;
      }
    },
    elementIsActive(element) {
      if (element.active !== undefined) {
        return element.active;
      } else if (this.currentLevel + 1 <= this.expandDefaultLevel) {
        return true;
      } else {
        return false;
      }
    },
    onClickItem(element) {
      if (!this.selectable) {
        this.$bus.emit("treeSelectedItem", element);
        element.active = !this.elementIsActive(element);
      }
    },
    onClickIcon(element) {
      if (this.selectable) {
        element.active = !this.elementIsActive(element);
      }
    },
    onClickName(element) {
      if (this.selectable) {
        this.$bus.emit("treeSelectedItem", element);
      }
    },
  },
};
</script>
<style lang="scss" scoped>
.ghost {
  @apply opacity-50 border-t;
}
</style>
