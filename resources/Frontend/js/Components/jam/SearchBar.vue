<template>
  <div
    class="border rounded-[10px] searchBox-shadow bg-gray-0 xl:py-[19px] md:py-[13px] py-[9px] xl:px-[32px] md:px-[22px] px-[10px] lg:w-[870px] md:w-[680px] absolute md:top-[-45px] top-[-90px] md:left-1/2 left-[10px] max-md:right-[10px] transform md:-translate-x-1/2 z-20"
  >
    <div
      class="md:py-[8px] md:px-[8px] md:flex items-center justify-center lg:space-x-[16px] md:space-x-[8px] w-full max-md:space-y-[10px]"
    >
      <JSearchBooking />
    </div>

    <!-- 
    <div
      class="md:py-[8px] md:px-[8px] md:flex items-center justify-center lg:space-x-[16px] md:space-x-[8px] w-full max-md:space-y-[10px]"
    >
      <div class="md:flex items-center md:space-x-[8px] space-x-[4px] w-full max-md:space-y-[10px]">
        <label
          class="block md:pl-[20px] pl-[10px] pr-[9.5px] py-[9.5px] space-x-[2px] bg-green-0 w-full"
        >
          <JFieldSet
            v-model="form.dateIn"
            :field="{
              errors: errors,
              type: 'date',
              placeholder: 'Ngày nhận phòng',
              isSubmit: isSubmit,
            }"
          />
        </label>
        <label
          class="block md:pl-[20px] pl-[10px] pr-[9.5px] py-[9.5px] space-x-[2px] bg-green-0 w-full"
        >
          <JFieldSet
            v-model="form.dateOut"
            :field="{
              errors: errors,
              type: 'date',
              placeholder: 'Ngày trả phòng',
              isSubmit: isSubmit,
            }"
          />
        </label>
      </div>
      <div class="max-md:w-full">
        <button
          class="p2 text-gray-0 max-md:w-full hover:bg-yellow-700 duration-150 w-max font-semibold space-x-[8px] py-[8px] px-[20px] bg-yellow-600 rounded-md flex items-center md:justify-start justify-center"
        >
          <JIcon name="search" />
          <span>TÌM KIẾM</span>
        </button>
      </div>
    </div> 
    -->
  </div>
</template>
<script>
import { validateForm } from "@/lib/validator.js";
const empty_form = {
  dateIn: "",
  dateOut: "",
};
export default {
  data() {
    return {
      processing: false,
      errors: {},
      rules: {
        dateIn: "required",
        dateOut: "required|phone",
        email: "required|email",
      },
      form: {
        ...empty_form,
      },
      isSubmit: false,
      isLoading: false,
      submitButton: true,
    };
  },
};
</script>
<style lang="scss" scoped>
.searchBox-shadow {
  box-shadow: 0px 8px 25px 0px #17161626;
}
</style>
