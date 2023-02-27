<template>
  <section>
    <div
      v-if="
        field.type === 'text' ||
        field.type === 'password' ||
        field.type === 'email'
      "
    >
      <label
        :for="field.placeholder"
        class="font-bold text-gray-700 mb-[4px] block"
      >
        {{ field.name }}<span v-if="isRequired"> *</span>
      </label>
      <input
        :id="field.placeholder"
        :type="field.type"
        :value="modelValue"
        :placeholder="field.placeholder"
        :disabled="field.disabled ? true : false"
        autocomplete="off"
        :class="isOrderPage ? 'input-order' : 'input-form'"
        @input="$emit('update:modelValue', $event.target.value)"
      />
    </div>

    <div v-if="field.type === 'number'">
      <label
        :for="field.placeholder"
        class="mb-[4px] font-bold text-gray-700 block"
      >
        {{ field.name }}<span> *</span>
      </label>
      <input
        :id="field.placeholder"
        :type="field.type"
        :value="modelValue"
        :placeholder="field.placeholder"
        :disabled="field.disabled ? true : false"
        autocomplete="off"
        :class="isOrderPage ? 'input-order' : 'input-form'"
        inputmode="numeric"
        onkeypress="return event.charCode >= 48 && event.charCode =< 57"
        onkeydown="return event.keyCode !== 69 && event.keyCode !== 190"
        @input="$emit('update:modelValue', $event.target.value)"
      />
    </div>

    <div v-if="field.type === 'textarea'">
      <label
        :for="field.placeholder"
        class="mb-[4px] font-bold text-gray-700 block"
      >
        {{ field.name }}<span v-if="isAddress"> *</span>
      </label>
      <textarea
        :id="field.placeholder"
        :type="field.type"
        :value="modelValue"
        :placeholder="field.placeholder"
        autocomplete="off"
        :rows="isContact ? '4' : '5'"
        :class="
          isOrderPage
            ? 'block w-full font-medium rounded py-[8px] px-[12px] text-gray-800 bg-white focus:bg-white border focus:border-gray-800 border-gray-300 focus:ring-0 outline-none focus:outline-none focus:duration-200'
            : 'block w-full font-bold rounded py-[10px] px-[20px] text-gray-700 bg-white focus:bg-white border focus:border-gray-800 border-gray-300 focus:ring-0 outline-none focus:outline-none focus:duration-200'
        "
        @input="$emit('update:modelValue', $event.target.value)"
      />
    </div>

    <div v-if="field.type === 'media_upload'">
      <InputFileMedia
        :modelValue="modelValue"
        @update:modelValue="$emit('update:modelValue', $event)"
        :field="field"
      />
    </div>

    <div v-if="field.type === 'select_single'">
      <label
        :for="field.placeholder"
        class="mb-[4px] text-gray-700 inline-block font-bold"
      >
        {{ field.name }}<span> *</span>
      </label>
      <select
        v-model="modelValue"
        class="select-style"
        :class="
          !modelValue || (!modelValue && field.placeholder)
            ? 'text-gray-600'
            : ' text-gray-800 font-medium'
        "
        @input="$emit('update:modelValue', $event.target.value)"
      >
        <option v-if="field.placeholder" value="" selected disabled>
          {{ field.placeholder }}
        </option>
        <option
          v-for="(item, id) in field.options"
          :key="id"
          :value="item.id || item.code"
        >
          {{ item.label || item.name_with_type }}
        </option>
      </select>
    </div>

    <div v-if="field.type === 'select_administrative'">
      <select
        v-model="select"
        class="list block w-full px-0 pt-0 pb-[10px] text-gray-200 bg-transparent border-0 border-b border-gray-500 focus:ring-0 focus:border-gray-500"
      >
        <option class="hidden" value="" disabled selected>
          {{ field.placeholder }}
        </option>
        <option
          v-for="(item, index) in field.options"
          :key="index"
          :value="item.key"
        >
          {{ item.value }}
        </option>
      </select>
    </div>

    <template v-if="field.type === 'radio'">
      <label class="text-gray-700"> {{ field.name }} <span> *</span> </label>
      <div
        v-if="field.options && field.options.length > 0"
        class="relative flex space-y-3 flex-col mt-[16px]"
      >
        <template v-for="(item, index) in field.options" :key="index">
          <div
            class="radio"
            :class="
              modelValue === item.value
                ? 'text-red-500 border-red-500'
                : 'text-gray-700 border-gray-200'
            "
          >
            <input
              :id="`${fieldId}${index}`"
              v-model="modelValue"
              class="border border-gray-500 radio-item"
              type="radio"
              :name="`${fieldId}`"
              :value="item.value"
              @input="$emit('update:modelValue', $event.target.value)"
            />
            <label
              class="block h-full radio-label w-max"
              :for="`${fieldId}${index}`"
              v-html="item.label"
            />
            <span class="checkmark"></span>
          </div>
        </template>
      </div>
      <div v-else class="flex items-center space-x-[8px]">
        <input
          :id="fieldId"
          v-model="modelValue"
          class="w-[18px] h-[18px]"
          type="radio"
          :value="field.value"
          :name="field.name"
        />
        <label
          v-if="field.label"
          :for="fieldId"
          class="relative self-center font-normal text-gray-900"
        >
          <span v-html="field.label"></span>
        </label>
      </div>
    </template>
    <template v-else-if="field.type === 'radio_custom'">
      <label
        class="p2 font-bold text-black xl:mb-[16px] md:mb-[12px] mb-[8px] block"
      >
        {{ field.label }}
      </label>
      <div class="space-y-[16px]">
        <template v-for="(item, index) in field.options" :key="index">
          <div class="relative flex items-center radio-control">
            <input
              type="radio"
              :value="item.id"
              :id="`${fieldId}${index}`"
              :name="`${fieldId}${index}`"
              v-model="modelValue"
              @input="$emit('update:modelValue', $event.target.value)"
              class="cursor-pointer"
            />
            <label
              :for="`${fieldId}${index}`"
              class="md:flex items-center md:space-x-[8px] xl:pl-[36px] md:pl-[32px] pl-[28px] cursor-pointer"
            >
              <div class="hidden md:block">
                <JPicture :src="item.icon" class="object-cover w-full h-full" />
              </div>
              <div
                class="relative z-10 inline-block font-medium text-gray-600 cursor-pointer"
              >
                {{ item.label }}
              </div>
            </label>
            <span class="checkmark"></span>
          </div>
          <BankingInfo v-if="modelValue == '2' && modelValue == item.id" />
        </template>
      </div>
    </template>
  </section>
</template>

<script>
import InputFileMedia from "../Fields/InputFileMedia.vue";
import BankingInfo from "../../Components/BankingInfo.vue";
export default {
  components: {
    BankingInfo,
    InputFileMedia,
  },
  props: [
    "field",
    "value",
    "isContact",
    "modelValue",
    "isAddress",
    "isOrderPage",
  ],
  emits: ["update:modelValue", "validate"],

  data() {
    return {
      model: this.modelValue,
      select: this.modelValue,
      fieldId: "",
      option: this.modelValue,
      isChecked: 1,
      isRequired: false,
    };
  },

  watch: {
    select: {
      handler() {
        this.$emit("input", this.select);
      },
    },

    value(value) {
      this.select = value;
      this.model = value;
    },
  },

  created() {
    this.fieldId = Math.random().toString(36).substr(2, 9);
    this.checkRequired();
  },
  mounted() {},

  methods: {
    handleInput(e) {
      this.$emit("input", e.target.value);
    },

    handleInputCheckbox(e) {
      this.$emit("input", e.target.checked);
    },

    inputChange(value) {
      this.$emit("input", value);
    },

    handleSelect(e) {
      this.$emit("select", e.target.value);
    },

    checkRequired() {
      this.isRequired = this.field.rules[this.field.fieldName]
        ? this.field.rules[this.field.fieldName].includes("required")
        : false;
    },
  },
};
</script>

<style lang="scss" scoped>
.input-form {
  @apply mt-0 block w-full font-bold rounded lg:py-[12px]	py-[8px] lg:px-[20px]	md:px-[14px]	px-[10px] text-gray-700 bg-white focus:bg-white border border-gray-300 focus:ring-0 focus:border-gray-800  outline-none focus:outline-none focus:duration-200;
  &:focus {
    @apply duration-200;
  }
}
.input-order {
  @apply mt-0 block w-full font-bold rounded py-[8px]	px-[12px] text-gray-700 bg-white focus:bg-white border border-gray-300 focus:ring-0 focus:border-gray-800  outline-none focus:outline-none focus:duration-200;
  &:focus {
    @apply duration-200;
  }
}

.input-media {
  @apply mt-0 block w-full font-bold rounded py-[12px]	px-[12px] text-gray-700 bg-white focus:bg-white border border-gray-300 focus:ring-0 focus:border-gray-800  outline-none focus:outline-none focus:duration-200;
  &:focus {
    @apply duration-200;
  }
}

::placeholder {
  @apply text-gray-500 font-medium;
}

.input-form:focus::placeholder {
  @apply duration-300;
}

textarea:focus::placeholder {
  @apply duration-300;
}

input[type="text"]:disabled {
  background: #ececed;
}
input[type="number"]:disabled {
  background: #ececed;
}
input[type="password"]:disabled {
  background: #ececed;
}
input[type="email"]:disabled {
  background: #ececed;
}

.select-style {
  @apply mt-0 block w-full rounded py-[8px]	px-[12px]  bg-white focus:bg-white border border-gray-300 focus:ring-0 focus:border-gray-300  outline-none focus:outline-none;
}
:deep {
  .radio-control {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    @apply relative;
    .checkmark {
      @apply absolute top-1/2 left-0 transform -translate-y-1/2 w-[20px] h-[20px] border-gray-500 border rounded-full;
      &:before {
        @apply block w-[16px] h-[16px] rounded-full;
        content: "";
      }
    }
    input {
      @apply absolute opacity-0 top-0 left-0 w-[20px] h-[20px] m-0 z-[1];
      &:checked {
        ~ .checkmark {
          @apply border-black;
          &:before {
            @apply bg-no-repeat w-[12px] h-[12px] bg-center bg-black ml-[3px] mt-[3px];
          }
        }
      }
    }
  }
}
</style>
