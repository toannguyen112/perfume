<template>
  <div class="star-rating">
    <label
      :key="rating"
      class="star"
      v-for="rating in ratings"
      :class="{
        'is-active': rating <= editStar && editStar != null,
        'is-disabled': disabled,
      }"
      @click="setRating(rating)"
      @mouseover="starOver(rating)"
      @mouseout="starOut"
    >
      <input
        class="star-checked"
        type="radio"
        :value="rating"
        :name="name"
        v-model="editStar"
        :disabled="disabled"
      />
      <svg
        class="w-[32px] h-[32px]"
        width="14"
        height="14"
        viewBox="0 0 14 14"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M3.85737 8.49665L3.52803 8.87286L3.85737 8.49665L0.898115 5.9061L4.83228 5.53443C5.09111 5.50997 5.31862 5.34763 5.42387 5.10697L7 1.50339L8.57613 5.10697L9.03423 4.90661L8.57613 5.10698C8.68139 5.34763 8.90889 5.50997 9.16772 5.53443L13.1019 5.9061L10.1426 8.49665L10.472 8.87286L10.1426 8.49665C9.94564 8.6691 9.85742 8.93577 9.91517 9.19285L10.7747 13.019L7.36414 11.0227C7.13931 10.8911 6.86069 10.8911 6.63586 11.0227L6.88843 11.4542L6.63585 11.0227L3.22526 13.019L4.08483 9.19286C4.14258 8.93576 4.05436 8.66909 3.85737 8.49665Z"
          stroke="#19191A"
        />
      </svg>

      <!-- <span class="text-center mt-2.5 caption font-medium text-grey-500">{{
        starLabels[rating - 1]
      }}</span> -->
    </label>
  </div>
  <div class="text-center p2 leading-[150%] text-gray-800 font-bold mt-[10px]">
    {{ text }}
  </div>
</template>

<script>
export default {
  props: {
    name: "",
    star: null,
    starLabels: [],
    id: "",
    disabled: false,
    required: false,
  },
  data() {
    return {
      editStar: this.star,
      changeValue: null,
      ratings: [1, 2, 3, 4, 5],
      text: this.starLabels[this.star - 1],
    };
  },
  methods: {
    /* Behaviour of the stars on mouseover. */
    starOver: function (index) {
      if (!this.disabled) {
        this.changeValue = this.editStar;
        this.editStar = index;
        this.text = this.starLabels[index - 1];
      }
    },
    /* Behaviour of the stars on mouseout. */
    starOut: function () {
      if (!this.disabled) {
        this.editStar = this.changeValue;
        this.text = this.starLabels[this.changeValue - 1];
      }
    },
    /* Set the rating. */
    setRating: function (value) {
      this.$emit("onSetRating", value);
      if (!this.disabled) {
        this.changeValue = value;
        this.editStar = value;
        this.text = this.starLabels[value - 1];
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.star-rating {
  @apply flex justify-center;
  .star {
    @apply flex items-center flex-col px-[8px];
    path {
      @apply transition-all duration-200 ease-in-out;
    }
    &:not(.is-disabled) {
      cursor: pointer;
    }
    &.is-active {
      path {
        fill: #19191a;
      }
    }
  }
  .star-checked {
    @apply absolute overflow-hidden w-px h-px -m-px p-0 border-0;
    clip: rect(0 0 0 0);
  }
}
</style>
