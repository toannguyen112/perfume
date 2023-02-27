<template>
  <div
    class="flex border border-gray-300 rounded-[5px] items-center py-[7px] w-[108px]"
  >
    <button class="px-[8px] py-[2px] p-[2px]" @click="decrement()">
      <JIcon
        name="minus"
        class="text-gray-400 duration-200 cursor-pointer stroke-current transition-color hover:text-gray-600"
      />
    </button>
    <input
      ref="input"
      v-model="quantity"
      class="px-[6px] py-0 font-bold text-black text-center border-0 border-l border-r border-gray-300 outline-none focus:outline-none ring-0 focus:ring-0 shadow-none w-[36px]"
      type="number"
      step="1"
      autocomplete="off"
      @input="change($event.target.value)"
    />
    <button class="px-[8px] py-[2px] p-[2px]" @click="increment()">
      <JIcon
        name="plus"
        class="text-gray-400 duration-200 cursor-pointer stroke-current transition-color hover:text-gray-600"
      />
    </button>
  </div>
</template>

<script>
// const REGEXP_NUMBER = /^-?(?:\d+|\d+\.\d+|\.\d+)(?:[eE][-+]?\d+)?$/;
// const REGEXP_DECIMALS = /\.\d*(?:0|9){10}\d*$/;
// const normalizeDecimalNumber = (value, times = 100000000000) =>
//   REGEXP_DECIMALS.test(String(value))
//     ? Math.round(value * times) / times
//     : value;

export default {
  props: {
    number: {
      type: Number,
      default: 1,
    },

    max: {
      type: Number,
      default: 99,
    },

    min: {
      type: Number,
      default: 1,
    },
  },
  data() {
    return {
      quantity: this.number * 1,
      // min: 1,
      // max: 99,
    };
  },
  watch: {
    quantity(value) {
      if (value < 1) {
        this.quantity = 1;
        return;
      }

      this.$emit("change-quanity", value);
    },
    number(value) {
      this.quantity = value;
    },
  },
  methods: {
    increment() {
      return ++this.quantity;
    },
    decrement() {
      return this.quantity > 1 ? --this.quantity : 1;
    },

    change(value) {
      const quantity = Number(value);
      if (quantity > 0) {
        this.quantity = quantity;
      } else {
        this.$refs.input.value = String(this.quantity);
      }
    },
  },
};
</script>
