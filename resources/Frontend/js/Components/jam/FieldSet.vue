<template>
  <fieldset>
    <div v-if="field.type === 'media_upload'">
      <JField
        :value="value"
        @update:modelValue="$emit('update:modelValue', $event)"
        :modelValue="modelValue"
        :field="field"
        :isRecruitment="booleanRecruitment"
      />
    </div>
    <div
      v-if="
        field.type === 'text' ||
        field.type === 'email' ||
        field.type === 'password' ||
        field.type === 'textarea' ||
        field.type === 'number' ||
        field.type === 'select_single' ||
        field.type === 'select_administrative'
      "
      class="grid grid-cols-12 gap-x-[16px] gap-y-[2px]"
    >
      <div
        :class="field.row ? 'md:col-span-7 col-span-full' : 'col-span-full'"
        class="relative"
      >
        <JField
          @action="$emit('action')"
          :modelValue="modelValue"
          @update:modelValue="$emit('update:modelValue', $event)"
          :field="field"
          :validate="validate"
          :isContact="contactForm"
          :isAddress="isAddress"
          :isOrderPage="isOrderPage"
        />
        <small
          v-if="validate !== true && validate !== undefined"
          class="absolute flex items-center h-5 mt-[2px] text-xs errors"
          :class="[
            field.type === 'media_upload' || field.type === 'textarea'
              ? ' bottom-[-15%]'
              : 'mt-[-13px]  bottom-[-28%]',
            contactForm ? 'text-green-200' : 'text-red-500',
          ]"
        >
          {{
            Array.isArray(error) ? error[0] : `Quý khách vui lòng nhập ${label}`
          }}
        </small>
      </div>
    </div>
    <div
      v-if="field.type === 'radio' || field.type === 'radio_custom'"
      class="relative"
    >
      <JField
        @update:modelValue="$emit('update:modelValue', $event)"
        :modelValue="modelValue"
        :field="field"
      />
      <small
        v-if="validate !== true && validate !== undefined"
        class="
          absolute
          bottom-[-30%]
          flex
          items-center
          h-5
          mt-[2px]
          text-xs
          errors
        "
        :class="[
          field.type === 'media_upload' || field.type === 'textarea'
            ? ''
            : 'mt-[-13px]',
          contactForm ? 'text-green-200' : 'text-red-500',
        ]"
      >
        {{
          Array.isArray(error)
            ? error[0]
            : `Quý khách vui lòng chọn ${field.name}`
        }}
      </small>
    </div>
    <div v-if="field.type === 'radio_control'">
      <JField v-model="model" :field="field" />
    </div>
    <div v-if="field.type === 'date'">
      <JField v-model="model" :field="field" />
    </div>
  </fieldset>
</template>

<script>
import { validateField } from "@/lib/validator.js";
export default {
  props: [
    "field",
    "value",
    "classGrid",
    "classCol",
    "contactForm",
    "modelValue",
    "isAddress",
    "isOrderPage",
  ],

  data() {
    return {
      model: this.modelValue,
      validate: this.field.validate === false ? this.field.validate : true,
      label: this.field.label ? this.field.label.replace("<br />", "") : "",
    };
  },

  computed: {
    error() {
      return this.field.fieldName
        ? this.field.errors[this.field.fieldName]
        : "";
    },
  },

  watch: {
    model(value) {
      if(this.field.isSubmit){
      }else{
        this.validate = validateField(
        value,
        this.field.rules[this.field.fieldName]
      );
      this.$emit("input", value);
      }
    },

    error() {
      this.checkValidate();
    },

    modelValue(value) {
      this.model = value;
    },
  },

  created() {
    this.checkValidate();
  },

  methods: {
    changeImage(value) {
      this.$emit("changeImages", value);
    },

    checkValidate() {
      this.validate = !this.field.errors.hasOwnProperty(this.field.fieldName);
    },
  },
};
</script>
